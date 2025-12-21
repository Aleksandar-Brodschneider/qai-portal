<script setup>
import { ref } from 'vue'

const apiResult = ref('')
const apiError = ref('')

const testApi = async () => {
  apiResult.value = ''
  apiError.value = ''
  try {
    const response = await fetch('http://127.0.0.1:8000/api/test', {
      headers: { Accept: 'application/json' },
    })
    if (!response.ok) throw new Error(`HTTP ${response.status}`)
    apiResult.value = JSON.stringify(await response.json(), null, 2)
  } catch (e) {
    apiError.value = String(e)
  }
}
</script>

<template>
  <main class="page">
    <h1>HomeView (router)</h1>

    <button @click="testApi">Test API</button>

    <p v-if="apiError">Napaka: {{ apiError }}</p>
    <pre v-if="apiResult">{{ apiResult }}</pre>
  </main>
</template>

<style scoped>
.page { padding: 24px; }
</style>