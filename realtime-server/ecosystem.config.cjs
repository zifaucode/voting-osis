module.exports = {
  apps: [
    {
      name: "pemilos-realtime",
      script: "server.js",
      cwd: __dirname,
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: "100M",
      env: {
        PORT: 3001,
        REALTIME_SECRET: "pemilos_secret_broadcast_key_2026",
        NODE_ENV: "production"
      }
    }
  ]
};
