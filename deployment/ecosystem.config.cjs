// ==============================================================================
// PEMILOS PM2 PROCESS MANAGER ECOSYSTEM
// ==============================================================================
// Jalankan di root project: pm2 start deployment/ecosystem.config.cjs
// Simpan autostart: pm2 save && pm2 startup

module.exports = {
  apps: [
    {
      name: "pemilos-realtime",
      script: "realtime-server/server.js",
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: "200M",
      env: {
        NODE_ENV: "production",
        PORT: 3001,
        REALTIME_SECRET: process.env.REALTIME_SECRET || "pemilos_secret_broadcast_key_2026",
      },
      error_file: "storage/logs/pm2-realtime-error.log",
      out_file: "storage/logs/pm2-realtime-out.log",
      time: true,
    },
    {
      name: "pemilos-queue-worker",
      script: "artisan",
      interpreter: "php",
      args: ["queue:work", "database", "--sleep=1", "--tries=3", "--max-time=3600", "--memory=128"],
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: "150M",
      error_file: "storage/logs/pm2-queue-error.log",
      out_file: "storage/logs/pm2-queue-out.log",
      time: true,
    },
  ],
};
