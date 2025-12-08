import type { ThemeConfig } from 'antd';

export const antdTheme: ThemeConfig = {
  token: {
    // Kenya Government Colors
    colorPrimary: '#006600',      // Kenya green
    colorSuccess: '#006600',
    colorWarning: '#FF0000',      // Kenya red
    colorError: '#FF0000',
    colorInfo: '#0D6EFD',
    colorLink: '#006600',
    
    // Typography
    fontFamily: 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
    fontSize: 14,
    fontSizeHeading1: 38,
    fontSizeHeading2: 30,
    fontSizeHeading3: 24,
    fontSizeHeading4: 20,
    fontSizeHeading5: 16,
    
    // Spacing & Layout
    borderRadius: 8,
    controlHeight: 40,
    
    // Colors
    colorBgContainer: '#FFFFFF',
    colorBgLayout: '#F5F5F5',
    colorBgElevated: '#FFFFFF',
    colorBorder: '#D9D9D9',
    colorBorderSecondary: '#F0F0F0',
    
    // Text
    colorText: '#000000',
    colorTextSecondary: 'rgba(0, 0, 0, 0.65)',
    colorTextTertiary: 'rgba(0, 0, 0, 0.45)',
    colorTextQuaternary: 'rgba(0, 0, 0, 0.25)',
  },
  components: {
    Layout: {
      headerBg: '#FFFFFF',
      headerHeight: 64,
      headerPadding: '0 24px',
      headerColor: '#000000',
      siderBg: '#001529',
      bodyBg: '#F5F5F5',
      footerBg: '#FAFAFA',
      footerPadding: '24px 50px',
    },
    Menu: {
      darkItemBg: '#001529',
      darkItemSelectedBg: '#006600',
      darkItemHoverBg: 'rgba(0, 102, 0, 0.2)',
      itemBorderRadius: 8,
    },
    Button: {
      controlHeight: 40,
      controlHeightLG: 48,
      controlHeightSM: 32,
      borderRadius: 8,
      borderRadiusLG: 8,
      borderRadiusSM: 6,
      fontWeight: 500,
    },
    Input: {
      controlHeight: 40,
      controlHeightLG: 48,
      controlHeightSM: 32,
      borderRadius: 8,
      paddingBlock: 8,
      paddingInline: 12,
    },
    Select: {
      controlHeight: 40,
      borderRadius: 8,
    },
    Table: {
      headerBg: '#FAFAFA',
      headerColor: '#000000',
      headerSortActiveBg: '#F0F0F0',
      headerSortHoverBg: '#F5F5F5',
      borderRadius: 8,
      rowHoverBg: '#F5F5F5',
    },
    Card: {
      borderRadius: 12,
      boxShadow: '0 1px 2px 0 rgba(0, 0, 0, 0.03), 0 1px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px 0 rgba(0, 0, 0, 0.02)',
    },
    Modal: {
      borderRadius: 12,
      headerBg: '#FFFFFF',
    },
    Drawer: {
      borderRadius: 0,
    },
    Form: {
      labelFontSize: 14,
      labelColor: 'rgba(0, 0, 0, 0.85)',
      itemMarginBottom: 24,
    },
    Tabs: {
      itemActiveColor: '#006600',
      itemHoverColor: '#006600',
      itemSelectedColor: '#006600',
      inkBarColor: '#006600',
    },
    Badge: {
      dotSize: 8,
    },
    Tag: {
      borderRadius: 6,
    },
    Alert: {
      borderRadius: 8,
    },
    Message: {
      contentBg: '#FFFFFF',
      borderRadius: 8,
    },
    Notification: {
      borderRadius: 8,
    },
  },
};
