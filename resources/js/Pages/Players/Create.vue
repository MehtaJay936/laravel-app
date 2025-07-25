<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePlayerStore } from '@/Store/playerStore.js'
import { storeToRefs } from 'pinia';

const playerStore = usePlayerStore();
const { playerForm } = storeToRefs(playerStore);

const valid = ref(false)
const form = ref()
const loading = ref(false)

const nameRules = [
  value => {
    if (value) return true

    return 'Name is required.'
  },
]

const emailRules = [
  value => {
    if (value) return true

    return 'Email is required.'
  },
]

const submit = async () => {
  const { valid } = await form.value.validate();

  if (valid) {
    loading.value = true;
    playerStore.createPlayer()
      .then(() => {
        loading.value = false;
        router.visit(route('players'));
      })
      .catch((error) => {
        loading.value = false;
        console.error('Error creating player:', error);
      });
  }
};

</script>

<template>

  <Head title="Create Players" />

  <AuthenticatedLayout>
    <div class="p-4">
      <v-card flat>

        <v-form ref="form" v-model="valid" @submit.prevent="submit">
          <v-container>
            {{ playerForm }}
            <v-row>
              <v-col cols="12" md="4">
                <v-text-field v-model="playerForm.name" :rules="nameRules" label="Name" variant="outlined" required
                  clearable></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field v-model="playerForm.email" :rules="emailRules" label="E-mail" variant="outlined" required
                  clearable></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field v-model="playerForm.date_of_birth" label="Date of Birth" variant="outlined" required
                  type="date" clearable></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-file-input v-model="playerForm.logo" label="Logo" variant="outlined" required clearable></v-file-input>
              </v-col>
            </v-row>

            <v-row justify="center" class="mb-2">
              <v-btn type="submit" color="primary" rounded="xl" :loading="loading" :disabled="loading">Save</v-btn>
            </v-row>
          </v-container>
        </v-form>

      </v-card>
    </div>
  </AuthenticatedLayout>
</template>
