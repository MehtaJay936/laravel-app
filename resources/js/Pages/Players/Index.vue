<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3';
import { usePlayerStore } from '@/Store/playerStore.js'
import { storeToRefs } from 'pinia';

const playerStore = usePlayerStore();
const { playersData } = storeToRefs(playerStore);

const search = ref('');

onMounted(async () => {
  await playerStore.getPlayers();
})

const headersList = [
  { title: 'ID', key: 'id', align: 'start' },
  { title: 'Name', key: 'name', align: 'center' },
  { title: 'Email', key: 'email', align: 'center' },
  { title: 'Date of  Birth', key: 'date_of_birth', align: 'center' },
  { title: 'Actions', key: 'actions', align: 'center', sortable: false },
]
</script>

<template>

  <Head title="Players" />

  <AuthenticatedLayout>
    <div class="p-4">

      <v-card flat>

        <v-card-title class="d-flex align-center pe-5">
          <v-text-field v-model="search" density="compact" label="Search" prepend-inner-icon="mdi-magnify"
            variant="outlined" flat hide-details single-line clearable></v-text-field>

          <v-spacer></v-spacer>

          <v-btn prepend-icon="mdi-plus" color="primary" :href="route('players-create')" rounded="xl">Create</v-btn>

        </v-card-title>

        <v-divider></v-divider>

        <v-data-table :headers="headersList" :items="playersData" :search="search" class="elevation-1">
          <template v-slot:item.actions="{ item }">
            <div class="d-flex ga-2 justify-center">
              <v-icon color="primary" icon="mdi-pencil" size="small" @click="edit(item.id)" title="Edit">
                <v-tooltip>
                  Edit Player
                </v-tooltip>
              </v-icon>

              <v-icon color="red" icon="mdi-delete" size="small" @click="remove(item.id)" title="Delete"></v-icon>
            </div>
          </template>
        </v-data-table>

      </v-card>
    </div>
  </AuthenticatedLayout>
</template>
