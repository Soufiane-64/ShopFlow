<template>
  <div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200">
      <div class="p-6">
        <h1 class="text-2xl font-bold text-primary-600">ShopFlow</h1>
        <p class="text-xs text-gray-500 mt-1">E-Commerce PM</p>
      </div>
      
      <nav class="px-4 space-y-1">
        <router-link 
          v-for="item in navigation" 
          :key="item.name"
          :to="item.path"
          class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors"
          active-class="bg-primary-50 text-primary-700 font-medium"
        >
          <component :is="item.icon" class="w-5 h-5 mr-3" />
          {{ item.name }}
        </router-link>
      </nav>
      
      <div class="absolute bottom-0 w-64 p-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-medium">
              {{ userInitials }}
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-700">{{ userName }}</p>
              <p class="text-xs text-gray-500">{{ userRole }}</p>
            </div>
          </div>
          <button @click="handleLogout" class="text-gray-400 hover:text-gray-600">
            <LogOut class="w-5 h-5" />
          </button>
        </div>
      </div>
    </aside>
    
    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { 
  LayoutDashboard, 
  FolderKanban, 
  GitBranch, 
  CheckSquare, 
  Users,
  LogOut 
} from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()

const navigation = [
  { name: 'Dashboard', path: '/', icon: LayoutDashboard },
  { name: 'Projects', path: '/projects', icon: FolderKanban },
  { name: 'Releases', path: '/releases', icon: GitBranch },
  { name: 'QA & Testing', path: '/qa', icon: CheckSquare },
  { name: 'Client Portal', path: '/client-portal', icon: Users }
]

const userName = computed(() => authStore.user?.name || 'User')
const userRole = computed(() => authStore.user?.role || 'user')
const userInitials = computed(() => {
  const name = userName.value
  return name.split(' ').map(n => n[0]).join('').toUpperCase()
})

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>
