export default function handler(req, res) {
  res.setHeader('Content-Type', 'application/javascript');
  res.setHeader('Cache-Control', 's-maxage=60, stale-while-revalidate');
  const apiUrl = process.env.VITE_API_URL || process.env.API_BASE_URL || 'https://betulinaja-production.up.railway.app';
  res.status(200).send(`window.__ENV__ = Object.assign(window.__ENV__ || {}, { VITE_API_URL: "${apiUrl}", API_BASE_URL: "${apiUrl}" });`);
}
