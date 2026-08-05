const fs = require('fs');
const path = require('path');

const args = process.argv.slice(2);
const TARGET_URL = args.find(a => a.startsWith('http'));
const MAX_PAGES = parseInt(args.find(a => a.startsWith('--max-pages='))?.split('=')[1]) || 0;
const WITH_DETAILS = args.includes('--with-details');
const OUTPUT = args.find(a => a.startsWith('--out='))?.split('=')[1] ||
               path.join(__dirname, '..', 'storage', 'app', 'autopartsway-products.json');

if (!TARGET_URL) {
  console.log('AutoPartsWay Scraper');
  console.log('');
  console.log('Usage:');
  console.log('  node autopartsway-scraper.js <url> [options]');
  console.log('');
  console.log('URL - page avec liste de produits:');
  console.log('  https://autopartsway.com/Fiat_parts.html');
  console.log('  https://autopartsway.com/Fiat_parts.html?filter%5Byear_description%5D=2020');
  console.log('');
  console.log('Options:');
  console.log('  --max-pages=N   Nombre max de pages (defaut: toutes)');
  console.log('  --with-details  RÃ©cupÃ¨re les descriptions depuis chaque fiche produit (plus lent)');
  console.log('  --out=file.json Fichier de sortie');
  console.log('');
  console.log('Exemples:');
  console.log('  node autopartsway-scraper.js "https://autopartsway.com/Fiat_parts.html" --max-pages=3');
  console.log('  node autopartsway-scraper.js "https://autopartsway.com/Fiat_parts.html" --with-details');
  process.exit(1);
}

async function fetchHTML(url) {
  const controller = new AbortController();
  const timeout = setTimeout(() => controller.abort(), 30000);

  try {
    const response = await fetch(url, {
      signal: controller.signal,
      headers: {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Accept': 'text/html,application/xhtml+xml',
        'Referer': 'https://autopartsway.com/',
      },
    });

    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return await response.text();
  } finally {
    clearTimeout(timeout);
  }
}

function extractProductsFromHTML(html, pageUrl) {
  const products = [];
  const cards = html.match(/<div\s+class="apw-pl-card"[^>]*>[\s\S]*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/g) || [];

  for (const cardHtml of cards) {
    try {
      const getAttr = (attr) => {
        let r = new RegExp(`${attr}="([^"]*)"`);
        let m = cardHtml.match(r);
        if (m) return m[1].replace(/&amp;/g, '&').replace(/&#039;/g, "'").replace(/&quot;/g, '"');
        r = new RegExp(`${attr}='([^']*)'`);
        m = cardHtml.match(r);
        return m ? m[1].replace(/&amp;/g, '&').replace(/&#039;/g, "'").replace(/&quot;/g, '"') : '';
      };

      const name = getAttr('data-productitle');
      if (!name) continue;

      const brand = getAttr('data-brand');
      const partNumber = getAttr('data-k');
      const priceStr = getAttr('data-price') || (cardHtml.match(/class="price"[^>]*>\$?([^<]+)/)?.[1] || '0');
      const price = parseFloat(priceStr.replace(/[^0-9.]/g, '')) || 0;
      const stockStr = getAttr('data-stock');
      const stock = parseInt(stockStr) || 0;

      const oldPriceMatch = cardHtml.match(/class="old"[^>]*>\$?([^<]+)/);
      const oldPrice = oldPriceMatch ? parseFloat(oldPriceMatch[1].replace(/[^0-9.]/g, '')) || null : null;

      const imgMatch = cardHtml.match(/<img[^>]+src="([^"]+)"[^>]*>/);
      let image = imgMatch ? imgMatch[1] : '';
      if (image && !image.startsWith('http')) {
        image = 'https://autopartsway.com' + (image.startsWith('/') ? '' : '/') + image;
      }

      let galleryImages = [];
      const imagesJson = getAttr('data-images');
      if (imagesJson) {
        try {
          const parsed = JSON.parse(imagesJson);
          if (Array.isArray(parsed)) {
            galleryImages = parsed.map(u => u.startsWith('http') ? u : 'https://autopartsway.com' + (u.startsWith('/') ? '' : '/') + u);
          }
        } catch (e) {}
      }

      const detailLinkMatch = cardHtml.match(/class="apw-pl-title-link"[^>]*href="([^"]+)"/);
      let detailUrl = detailLinkMatch ? detailLinkMatch[1] : '';
      if (detailUrl && !detailUrl.startsWith('http')) {
        detailUrl = 'https://autopartsway.com' + (detailUrl.startsWith('/') ? '' : '/') + detailUrl;
      }

      const make = getAttr('data-make');
      const model = getAttr('data-model');
      const year = getAttr('data-year');
      const engine = getAttr('data-engine');
      const category = getAttr('data-category');

      const compatibility = [];
      if (make) compatibility.push(year ? `${year} ${make} ${model}` : `${make} ${model}`);
      if (engine) compatibility.push(engine);

      const notes = [];
      const noteRegex = /<div\s+class="apw-pl-note"[^>]*>([\s\S]*?)<\/div>/g;
      let noteMatch;
      while ((noteMatch = noteRegex.exec(cardHtml)) !== null) {
        const t = noteMatch[1].replace(/<[^>]+>/g, '').trim();
        if (t) notes.push(t);
      }

      const vehicleMake = make || brand;
      const partManufacturer = brand !== make ? brand : null;

      products.push({
        name,
        price,
        old_price: oldPrice,
        brand: vehicleMake,
        manufacturer: partManufacturer,
        sku: partNumber,
        gallery_images: galleryImages.length > 1 ? galleryImages : null,
        description: null,
        compatibility: compatibility.join(', ') || null,
        image: image || null,
        stock_quantity: stock || 10,
        is_active: true,
        category: category || null,
        _detailUrl: detailUrl,
        _notes: notes,
        _model: model,
        _year: year,
      });
    } catch (e) {
      // skip
    }
  }

  return products;
}

async function fetchProductDetail(url) {
  try {
    const html = await fetchHTML(url);
    const result = { description: null, specifications: null, bestImage: null };

    const descTab = html.match(/<div[^>]*class="apw-pd-tab-pane apw-pd-copy[^"]*"[^>]*id="product-tab-description"[^>]*>([\s\S]*?)<\/div>\s*<\/div>/);
    if (descTab) {
      result.description = descTab[1]
        .replace(/<div[^>]*class="typography"[^>]*>/i, '')
        .replace(/<\/div>/g, '')
        .replace(/<[^>]+>/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
    }

    const specTab = html.match(/<div[^>]*class="apw-pd-tab-pane"[^>]*id="product-tab-specification"[^>]*>([\s\S]*?)<\/div>\s*<\/div>/);
    if (specTab) {
      const specRows = specTab[1].match(/<div\s+class="spec__row"[^>]*>([\s\S]*?)<\/div>\s*<\/div>/g);
      if (specRows) {
        const specs = [];
        specRows.forEach(row => {
          const nameMatch = row.match(/<div\s+class="spec__name"[^>]*>([\s\S]*?)<\/div>/);
          const valueMatch = row.match(/<div\s+class="spec__value"[^>]*>([\s\S]*?)<\/div>/);
          if (nameMatch && valueMatch) {
            specs.push(`${nameMatch[1].replace(/<[^>]+>/g, '').trim()}: ${valueMatch[1].replace(/<[^>]+>/g, '').trim()}`);
          }
        });
        result.specifications = specs.join('\n');
      }
    }

    const mainImgMatch = html.match(/<img[^>]*id="apw-pd-mainimg"[^>]*src="([^"]+)"/);
    if (mainImgMatch) {
      let img = mainImgMatch[1];
      if (img && !img.startsWith('http')) {
        img = 'https://autopartsway.com' + (img.startsWith('/') ? '' : '/') + img;
      }
      result.bestImage = img;
    }

    const thumbs = [];
    const thumbRegex = /<button[^>]*class="apw-pd-thumb[^"]*"[^>]*data-img="([^"]+)"/g;
    let thumbMatch;
    while ((thumbMatch = thumbRegex.exec(html)) !== null) {
      let t = thumbMatch[1];
      if (t && !t.startsWith('http')) {
        t = 'https://autopartsway.com' + (t.startsWith('/') ? '' : '/') + t;
      }
      thumbs.push(t);
    }
    if (thumbs.length > 1) result.galleryImages = thumbs;

    return result;
  } catch (err) {
    return { description: null, specifications: null, bestImage: null };
  }
}

function getPaginationInfo(html) {
  const showingMatch = html.match(/class="apw-pl-showing"[^>]*>([^<]+)/);
  const showingText = showingMatch ? showingMatch[1].trim() : '';

  const pageNums = [];
  const pageRegex = /page-number=(\d+)/g;
  let m;
  while ((m = pageRegex.exec(html)) !== null) {
    pageNums.push(parseInt(m[1]));
  }

  return {
    total: (() => {
      const m = showingText.match(/of\s+([\d,]+)/i);
      return m ? parseInt(m[1].replace(/,/g, '')) : null;
    })(),
    lastPage: pageNums.length > 1 ? Math.max(...pageNums) : 1,
    showingText,
  };
}

function buildPageUrl(baseUrl, pageNum) {
  let url = baseUrl;
  url = url.replace(/[?&]page-number=\d+/g, '');
  url = url.replace(/[?&]page-offset=\d+/g, '');
  const sep = url.includes('?') ? '&' : '?';
  return `${url}${sep}page-number=${pageNum}&page-offset=100`;
}

(async () => {
  console.log('AutoPartsWay Scraper');
  console.log(`URL: ${TARGET_URL}`);
  if (WITH_DETAILS) console.log('Mode: avec descriptions dÃ©taillÃ©es');
  if (MAX_PAGES > 0) console.log(`Pages max: ${MAX_PAGES}\n`);

  console.log('Page 1...');
  const html = await fetchHTML(buildPageUrl(TARGET_URL, 1));
  const pagination = getPaginationInfo(html);
  console.log(`Produits: ${pagination.total || '?'} | Pages: ${pagination.lastPage}`);

  const totalPages = MAX_PAGES > 0 ? Math.min(MAX_PAGES, pagination.lastPage) : pagination.lastPage;
  let allProducts = [];
  let totalFromCards = 0;

  for (let p = 1; p <= totalPages; p++) {
    try {
      const pageHtml = p === 1 ? html : await fetchHTML(buildPageUrl(TARGET_URL, p));
      const products = extractProductsFromHTML(pageHtml);
      allProducts = allProducts.concat(products);
      totalFromCards += products.length;
      if (p % 5 === 0 || p === totalPages) {
        process.stdout.write(`\rPage ${p}/${totalPages}: ${products.length} produits (total: ${totalFromCards})`);
      }
    } catch (err) {
      console.error(`\nPage ${p} erreur: ${err.message}`);
      break;
    }
  }

  process.stdout.write(`\n${totalFromCards} produits extraits des listes.\n`);

  if (WITH_DETAILS && allProducts.length > 0) {
    console.log(`RÃ©cupÃ©ration des descriptions dÃ©taillÃ©es pour ${allProducts.length} produits...`);
    let done = 0;
    for (const product of allProducts) {
      if (product._detailUrl) {
        process.stdout.write(`\r[${++done}/${allProducts.length}] ${product.name.substring(0, 50)}...`);
        const detail = await fetchProductDetail(product._detailUrl);
        product.description = detail.description || product._notes.join('; ') || null;
        product.specifications = detail.specifications;
        if (detail.bestImage) product.image = detail.bestImage;
        if (detail.galleryImages) product.gallery_images = detail.galleryImages;
      }
    }
    process.stdout.write('\n');
  } else {
    for (const product of allProducts) {
      product.description = product._notes.length ? product._notes.join('; ') : null;
    }
  }

  for (const p of allProducts) {
    delete p._detailUrl;
    delete p._notes;
    delete p._model;
    delete p._year;
  }

  const outputDir = path.dirname(OUTPUT);
  if (!fs.existsSync(outputDir)) fs.mkdirSync(outputDir, { recursive: true });
  fs.writeFileSync(OUTPUT, JSON.stringify(allProducts, null, 2), 'utf-8');

  console.log(`\nSauvegardÃ©: ${OUTPUT} (${allProducts.length} produits)`);

  if (allProducts.length > 0) {
    const s = allProducts[0];
    console.log(`\nExemple:`);
    console.log(`  Nom:    ${s.name}`);
    console.log(`  Prix:   $${s.price}`);
    console.log(`  Marque: ${s.brand}`);
    console.log(`  SKU:    ${s.sku}`);
    console.log(`  Image:  ${(s.image || '').substring(0, 80)}`);
    if (s.description) console.log(`  Desc:   ${s.description.substring(0, 80)}...`);
    if (s.specifications) console.log(`  Specs:  ${s.specifications.substring(0, 80)}...`);

    console.log(`\nImporter:`);
    console.log(`  php artisan products:import "${OUTPUT}" --download-images --category="Parts"`);
  }
})();
