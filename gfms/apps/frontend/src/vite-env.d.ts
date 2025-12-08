/// <reference types="vite/client" />

interface ImportMetaEnv {
  readonly VITE_APP_NAME: string;
  readonly VITE_APP_ENV: string;
  readonly VITE_API_BASE_URL: string;
  readonly VITE_APP_URL: string;
  readonly VITE_APP_DEBUG: string;
  readonly VITE_SANCTUM_STATEFUL_DOMAINS: string;
  readonly VITE_SESSION_DOMAIN: string;
  readonly VITE_ENABLE_ANALYTICS: string;
  readonly VITE_ENABLE_MAINTENANCE: string;
  readonly VITE_MAPBOX_ACCESS_TOKEN: string;
  readonly VITE_GOOGLE_ANALYTICS_ID: string;
  readonly VITE_SENTRY_DSN: string;
  readonly VITE_DEFAULT_LOCALE: string;
  readonly VITE_DEFAULT_TIMEZONE: string;
  readonly VITE_DATE_FORMAT: string;
  readonly VITE_TIME_FORMAT: string;
  readonly VITE_DATETIME_FORMAT: string;
}

interface ImportMeta {
  readonly env: ImportMetaEnv;
}
