<template>
  <div class="p-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-gray-600 mt-1">Welcome back! Here's your project overview.</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Active Projects</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.activeProjects }}</p>
          </div>
          <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
            <FolderKanban class="w-6 h-6 text-primary-600" />
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <TrendingUp class="w-4 h-4 text-green-500 mr-1" />
          <span class="text-green-600">+12%</span>
          <span class="text-gray-500 ml-2">from last month</span>
        </div>
      </div>
      
      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Tasks in Progress</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.tasksInProgress }}</p>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <CheckSquare class="w-6 h-6 text-blue-600" />
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-gray-600">{{ stats.tasksCompleted }} completed today</span>
        </div>
      </div>
      
      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Releases</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.releases }}</p>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <GitBranch class="w-6 h-6 text-green-600" />
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-gray-600">{{ stats.pendingReleases }} pending</span>
        </div>
      </div>
      
      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Team Members</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.teamMembers }}</p>
          </div>
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <Users class="w-6 h-6 text-purple-600" />
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-gray-600">Across all projects</span>
        </div>
      </div>
    </div>
    
    <!-- Recent Projects & Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="card">
        <h2 class="text-xl font-semibold mb-4">Recent Projects</h2>
        <div class="space-y-4">
          <div 
            v-for="project in recentProjects" 
            :key="project.id"
            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors"
            @click="$router.push(`/projects/${project.id}`)"
          >
            <div class="flex-1">
              <h3 class="font-medium text-gray-900">{{ project.name }}</h3>
              <p class="text-sm text-gray-500">{{ project.type }}</p>
            </div>
            <div class="flex items-center space-x-4">
              <div class="text-right">
                <div class="text-sm font-medium text-gray-900">{{ project.progress }}%</div>
                <div class="w-24 h-2 bg-gray-200 rounded-full mt-1">
                  <div 
                    class="h-full bg-primary-600 rounded-full" 
                    :style="{ width: project.progress + '%' }"
                  ></div>
                </div>
              </div>
              <span 
                class="px-3 py-1 text-xs font-medium rounded-full"
                :class="getStatusClass(project.status)"
              >
                {{ project.status }}
              </span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card">
        <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
        <div class="space-y-4">
          <div 
            v-for="activity in recentActivity" 
            :key="activity.id"
            class="flex items-start space-x-3"
          >
            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
              <component :is="activity.icon" class="w-4 h-4 text-primary-600" />
            </div>
            <div class="flex-1">
              <p class="text-sm text-gray-900">{{ activity.message }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ activity.time }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProjectsStore } from '../stores/projects'
import { 
  FolderKanban, 
  CheckSquare, 
  GitBranch, 
  Users,
  TrendingUp,
  FileText,
  AlertCircle,
  CheckCircle
} from 'lucide-vue-next'

const projectsStore = useProjectsStore()

const stats = ref({
  activeProjects: 12,
  tasksInProgress: 34,
  tasksCompleted: 8,
  releases: 5,
  pendingReleases: 2,
  teamMembers: 24
})

const recentProjects = ref([
  { id: 1, name: 'Shop-Relaunch 2025', type: 'Shopware 6', progress: 67, status: 'in_progress' },
  { id: 2, name: 'Mobile App Development', type: 'React Native', progress: 23, status: 'planning' },
  { id: 3, name: 'Payment Integration', type: 'Shopware 6', progress: 100, status: 'completed' }
])

const recentActivity = ref([
  { id: 1, icon: CheckCircle, message: 'Task "Design Homepage" completed', time: '5 minutes ago' },
  { id: 2, icon: GitBranch, message: 'Release v2.0.0 deployed to staging', time: '1 hour ago' },
  { id: 3, icon: FileText, message: 'New requirement added by client', time: '2 hours ago' },
  { id: 4, icon: AlertCircle, message: 'Bug reported in checkout process', time: '3 hours ago' }
])

const getStatusClass = (status) => {
  const classes = {
    'in_progress': 'bg-blue-100 text-blue-800',
    'planning': 'bg-yellow-100 text-yellow-800',
    'completed': 'bg-green-100 text-green-800',
    'on_hold': 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
  projectsStore.fetchProjects()
})
</script>
