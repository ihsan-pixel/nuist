import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.nuist.mobile',
  appName: 'Nuist Mobile',
  webDir: '../public',

  server: {
    allowNavigation: ['nuist.id'] // 🔥 WAJIB supaya tetap di dalam app
  }
};

export default config;
