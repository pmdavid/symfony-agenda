import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0',  // importante para Docker
    port: 5173,
    strictPort: true, // evita que busque otro puerto
  }
})
