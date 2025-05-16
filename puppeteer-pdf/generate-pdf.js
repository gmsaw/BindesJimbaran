const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
    try {
        const args = process.argv.slice(2);
        const url = args[0];
        const output = args[1];

        console.log("Starting Puppeteer PDF generation from URL:", url);

        const browser = await puppeteer.launch({
            headless: true,
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--allow-file-access-from-files',
                '--enable-local-file-accesses',
                '--disable-web-security'
            ]
        });

        const page = await browser.newPage();
        console.log("Navigating to URL:", url);

        await page.goto(url, {
            waitUntil: 'networkidle0',
            timeout: 60000
        });

        console.log("Page loaded. Generating PDF to:", output);

        await page.pdf({
            path: output,
            format: 'A4',
            landscape: true,
            printBackground: true
        });

        await browser.close();
        console.log("PDF generated successfully.");
    } catch (error) {
        console.error("Puppeteer error:", error.message);
        process.exit(1);
    }
})();
