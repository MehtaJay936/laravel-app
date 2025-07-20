<script setup>
import { ref, onMounted } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3';
import { usePlayerStore } from '@/Store/playerStore.js'
import { storeToRefs } from 'pinia';

const playerStore = usePlayerStore();
const { playerForm, playersData } = storeToRefs(playerStore);
const editDialog = ref(false);
const deleteDialog = ref(false)
const search = ref('');
const valid = ref(false)
const loading = ref(false)
const currentID = ref(null)

onMounted(async () => {
  await playerStore.getPlayers();
})

const editPlayer = async (id) => {
  currentID.value = id;

  await playerStore.fetchPlayer(currentID.value);
  editDialog.value = true;
}

const updatePlayer = () => {
  loading.value = true
  playerStore.updatePlayer()
    .then(() => {
      loading.value = false
      editDialog.value = false;
      playerStore.getPlayers();
    });
}

const showDeleteDialog = (id) => {
  currentID.value = id;
  deleteDialog.value = true;
}

const deletePlayer = () => {
  loading.value = true
  playerStore.deletePlayer(currentID.value)
    .then(() => {
      loading.value = false
      deleteDialog.value = false;
      playerStore.getPlayers();
    });
}

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
              <v-icon color="primary" icon="mdi-pencil" size="small" @click="editPlayer(item.id)" title="Edit">
                <v-tooltip>
                  Edit Player
                </v-tooltip>
              </v-icon>

              <v-icon color="red" icon="mdi-delete" size="small" @click="showDeleteDialog(item.id)" title="Delete">
                <v-tooltip>
                  Delete Player
                </v-tooltip>
              </v-icon>
            </div>
          </template>
        </v-data-table>

        <v-dialog v-model="editDialog" max-width="1000">
          <v-card title="Edit Player">
            <v-form ref="form" v-model="valid" @submit.prevent="">
              <v-container>
                <v-row>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="playerForm.name" :rules="nameRules" label="Name" variant="outlined" required
                      clearable></v-text-field>
                  </v-col>

                  <v-col cols="12" md="4">
                    <v-text-field v-model="playerForm.email" :rules="emailRules" label="E-mail" variant="outlined"
                      required clearable></v-text-field>
                  </v-col>

                  <v-col cols="12" md="4">
                    <v-text-field v-model="playerForm.date_of_birth" label="Date of Birth" variant="outlined" required
                      type="date" clearable :rules="dateRules"></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>

            <v-divider></v-divider>

            <v-card-actions class="bg-surface-light">
              <v-btn text="Cancel" variant="plain" @click="editDialog = false"></v-btn>

              <v-btn text="Save" color="primary" @click="updatePlayer()" :loading="loading" :disabled="loading"></v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="deleteDialog" max-width="1000">
          <v-card title="Delete Player">
            <v-card-text>
              Are you sure you want to delete this player?
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions class="bg-surface-light">
              <v-btn text="Cancel" variant="plain" @click="deleteDialog = false"></v-btn>

              <v-btn text="Delete" color="red" @click="deletePlayer()" :loading="loading" :disabled="loading"></v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

      </v-card>
    </div>
  </AuthenticatedLayout>
</template>
