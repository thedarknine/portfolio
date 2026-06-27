// .tools/unlighthouse.config.ts
import { defineUnlighthouseConfig } from 'unlighthouse/config'

export default defineUnlighthouseConfig({
  site: 'http://engine:80',
  server: {
    open: false,   // ne pas ouvrir le navigateur automatiquement
  },
  chrome: {
    useSystem: true,
    useDownloadFallback: false,
  },
  puppeteerOptions: {
    executablePath: '/usr/bin/chromium',
    args: [
      '--headless',
      '--no-sandbox',
      '--disable-setuid-sandbox',
      '--disable-dev-shm-usage',
      '--disable-gpu',
      '--no-zygote',
    ],
  },
  scanner: {
    robotsTxt: false,
    sitemap: true,
    maxRoutes: 100,
    include: ['/**'],
    exclude: ['/admin/**', '/api/**'],
  },
  client: {
    groupRoutesKey: 'route.definition.name',
  },
  hooks: {
    'worker-finished': async () => {
      process.exit(0)   // quitte proprement après le scan
    },
  },
  outputPath: './.tools/reports/unlighthouse',
})
