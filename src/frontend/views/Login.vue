<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100">
    <div class="card max-w-md w-full">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-primary-600">ShopFlow</h1>
        <p class="text-gray-600 mt-2">E-Commerce Project Management</p>
      </div>
      
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="label">Email</label>
          <input 
            v-model="email" 
            type="email" 
            class="input" 
            placeholder="admin@shopflow.dev"
            required
          />
        </div>
        
        <div>
          <label class="label">Password</label>
          <input 
            v-model="password" 
            type="password" 
            class="input" 
            placeholder="••••••••"
            required
          />
        </div>
        
        <div v-if="error" class="text-red-600 text-sm">
          {{ error }}
        </div>
        
        <button type="submit" class="btn btn-primary w-full" :disabled="loading">
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
      
      <div class="mt-6 text-center text-sm text-gray-600">
        <p>Demo credentials:</p>
        <p class="font-mono text-xs mt-1">admin@shopflow.dev / shopflow2024</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('admin@shopflow.dev')
const password = ref('shopflow2024')
const error = ref('')
const loading = ref(false)

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  
  const success = await authStore.login(email.value, password.value)
  
  if (success) {
    router.push('/')
  } else {
    error.value = 'Invalid credentials'
  }
  
  loading.value = false
}
</script>
