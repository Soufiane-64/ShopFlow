<template>
  <div class="p-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">QA & Testing</h1>
      <p class="text-gray-600 mt-1">Quality assurance checklists and test reports</p>
    </div>
    
    <!-- Test Reports -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="card">
        <p class="text-sm text-gray-600">Total Tests</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ reports.total_tests }}</p>
      </div>
      <div class="card">
        <p class="text-sm text-gray-600">Passed</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ reports.passed }}</p>
      </div>
      <div class="card">
        <p class="text-sm text-gray-600">Failed</p>
        <p class="text-3xl font-bold text-red-600 mt-1">{{ reports.failed }}</p>
      </div>
      <div class="card">
        <p class="text-sm text-gray-600">Coverage</p>
        <p class="text-3xl font-bold text-primary-600 mt-1">{{ reports.coverage }}%</p>
      </div>
    </div>
    
    <!-- Checklists -->
    <div class="card">
      <h2 class="text-xl font-semibold mb-4">E-Commerce Essentials Checklist</h2>
      <div class="space-y-2">
        <div 
          v-for="(item, index) in checklist" 
          :key="index"
          class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg"
        >
          <input type="checkbox" class="w-5 h-5 text-primary-600 rounded" />
          <span class="text-gray-900">{{ item }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const reports = ref({
  total_tests: 156,
  passed: 142,
  failed: 8,
  skipped: 6,
  coverage: 87.5
})

const checklist = ref([
  'Warenkorb-Funktionalität',
  'Checkout-Prozess (alle Payment-Provider)',
  'Responsive Design (Mobile/Tablet/Desktop)',
  'SEO-Meta-Tags',
  'Performance (PageSpeed > 90)',
  'Security (SQL-Injection, XSS)',
  'DSGVO-Konformität',
  'Barrierefreiheit (WCAG 2.1)'
])
</script>