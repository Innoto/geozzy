<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>http://www.galiciaagochada.com/EXEMPLO</loc>
    <lastmod>2015-12-23T18:00:15+00:00</lastmod>
  </url>
{foreach $urlsInfo as $info}
  <url>
    <loc>{$urlPrefix}{$info.loc}</loc>
    <lastmod>{$info.mod}</lastmod>
  </url>
{/foreach}
</urlset>
