const puppeteer = require('puppeteer');

(async () => {
    console.log("Starting browser...");
    const browser = await puppeteer.launch({ headless: "new" });
    const page = await browser.newPage();
    
    // Log console errors
    page.on('console', msg => {
        console.log(`BROWSER CONSOLE [${msg.type()}]: ${msg.text()}`);
    });
    
    page.on('pageerror', err => {
        console.log(`BROWSER ERROR: ${err.toString()}`);
    });
    
    // Log network requests
    page.on('request', request => {
        if (request.method() === 'POST') {
            console.log(`NETWORK POST REQUEST: ${request.url()}`);
        }
    });
    
    page.on('response', response => {
        if (response.request().method() === 'POST') {
            console.log(`NETWORK POST RESPONSE: ${response.url()} - Status: ${response.status()}`);
        }
    });

    console.log("Navigating to login page...");
    await page.goto('http://localhost:8001/login', { waitUntil: 'networkidle2' });
    
    console.log("Filling login form...");
    await page.type('#email', 'test@example.com');
    await page.type('#password', 'password');
    
    console.log("Clicking submit button...");
    await Promise.all([
        page.waitForNavigation({ timeout: 5000 }).catch(e => console.log("Navigation timeout - form might not have submitted.")),
        page.click('button[type="submit"]')
    ]);
    
    console.log("Checking current URL after submit: " + page.url());
    
    await browser.close();
})();
