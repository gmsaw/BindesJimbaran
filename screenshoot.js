import puppeteer from 'puppeteer';
import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';

// Untuk __dirname di ES Module
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const htmlPath = process.argv[2];
if (!htmlPath) {
    console.error('Path to HTML not provided.');
    process.exit(1);
}

const htmlContent = fs.readFileSync(htmlPath, 'utf8');

const browser = await puppeteer.launch();
const page = await browser.newPage();

await page.setContent(htmlContent, { waitUntil: 'networkidle0' });

await page.pdf({ path: 'output.pdf', format: 'A4', landscape: true });

await browser.close();
