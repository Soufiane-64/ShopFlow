<template>
  <div class="p-8">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
        <p class="text-gray-600 mt-1">Manage your e-commerce projects</p>
      </div>
      <button @click="showCreateModal = true" class="btn btn-primary flex items-center">
        <Plus class="w-5 h-5 mr-2" />
        New Project
      </button>
    </div>
    
    <!-- Filters -->
    <div class="card mb-6">
      <div class="flex items-center space-x-4">
        <div class="flex-1">
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Search projects..." 
            class="input"
          />
        </div>
        <select v-model="filterStatus" class="input w-48">
          <option value="">All Status</option>
          <option value="planning">Planning</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
        </select>
        <select v-model="filterType" class="input w-48">
          <option value="">All Types</option>
          <option value="shopware6">Shopware 6</option>
          <option value="modified">Modified</option>
          <option value="commerce_seo">Commerce:SEO</option>
        </select>
      </div>
    </div>
    
    <!-- Projects Grid -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
    </div>
    
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="project in filteredProjects" 
        :key="project.id"
        class="card hover:shadow-lg transition-shadow cursor-pointer"
        @click="$router.push(`/projects/${project.id}`)"
      >
        <div class="flex items-start justify-between mb-4">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900">{{ project.name }}</h3>
            <p class="text-sm text-gray-500 mt-1">{{ project.type }}</p>
          </div>
          <span 
            class="px-3 py-1 text-xs font-medium rounded-full"
            :class="getStatusClass(project.status)"
          >
            {{ project.status }}
          </span>
        </div>
        
        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ project.description }}</p>
        
        <div class="space-y-3">
          <div>
            <div class="flex items-center justify-between text-sm mb-1">
              <span class="text-gray-600">Progress</span>
              <span class="font-medium text-gray-900">{{ project.progress }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-200 rounded-full">
              <div 
                class="h-full bg-primary-600 rounded-full transition-all" 
                :style="{ width: project.progress + '%' }"
              ></div>
            </div>
          </div>
          
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center text-gray-600">
              <Calendar class="w-4 h-4 mr-1" />
              {{ formatDate(project.deadline) }}
            </div>
            <div class="flex items-center text-gray-600">
              <Users class="w-4 h-4 mr-1" />
              {{ project.team_members?.length || 0 }}
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Create Project Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
        <h2 class="text-2xl font-bold mb-6">Create New Project</h2>
        
        <form @submit.prevent="handleCreateProject" class="space-y-4">
          <div>
            <label class="label">Project Name</label>
            <input v-model="newProject.name" type="text" class="input" required />
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="label">Type</label>
              <select v-model="newProject.type" class="input" required>
                <option value="shopware6">Shopware 6</option>
                <option value="modified">Modified</option>
                <option value="commerce_seo">Commerce:SEO</option>
              </select>
            </div>
            
            <div>
              <label class="label">Status</label>
              <select v-model="newProject.status" class="input">
                <option value="planning">Planning</option>
                <option value="in_progress">In Progress</option>
              </select>
            </div>
          </div>
          
          <div>
            <label class="label">Description</label>
            <textarea v-model="newProject.description" class="input" rows="3"></textarea>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="label">Start Date</label>
              <input v-model="newProject.start_date" type="date" class="input" />
            </div>
            
            <div>
              <label class="label">Deadline</label>
              <input v-model="newProject.deadline" type="date" class="input" />
            </div>
          </div>
          
          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" @click="showCreateModal = false" class="btn btn-secondary">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary">
              Create Project
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useProjectsStore } from '../stores/projects'
import { Plus, Calendar, Users } from 'lucide-vue-next'

const projectsStore = useProjectsStore()

const loading = ref(false)
const searchQuery = ref('')
const filterStatus = ref('')
const filterType = ref('')
const showCreateModal = ref(false)

const newProject = ref({
  name: '',
  type: 'shopware6',
  status: 'planning',
  description: '',
  start_date: '',
  deadline: ''
})

const filteredProjects = computed(() => {
  let projects = projectsStore.projects
  
  if (searchQuery.value) {
    projects = projects.filter(p => 
      p.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (filterStatus.value) {
    projects = projects.filter(p => p.status === filterStatus.value)
  }
  
  if (filterType.value) {
    projects = projects.filter(p => p.type === filterType.value)
  }
  
  return projects
})

const getStatusClass = (status) => {
  const classes = {
    'in_progress': 'bg-blue-100 text-blue-800',
    'planning': 'bg-yellow-100 text-yellow-800',
    'completed': 'bg-green-100 text-green-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const formatDate = (date) => {
  if (!date) return 'No deadline'
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

const handleCreateProject = async () => {
  await projectsStore.createProject(newProject.value)
  showCreateModal.value = false
  newProject.value = {
    name: '',
    type: 'shopware6',
    status: 'planning',
    description: '',
    start_date: '',
    deadline: ''
  }
}

onMounted(async () => {
  loading.value = true
  await projectsStore.fetchProjects()
  loading.value = false
})
</script>
