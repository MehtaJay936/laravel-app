import { reactive, ref } from "vue";
import axios from "axios";
import { defineStore } from "pinia";

export const usePlayerStore = defineStore("player", () => {
    const playersData = ref([]);
    const playerForm = reactive({
        id: "",
        name: "",
        email: "",
        date_of_birth: "",
        logo: "",
    });

    const getPlayers = async () => {
        try {
            const { data } = await axios.get("/api/players");
            playersData.value = data.players;
        } catch (error) {
            console.error("Error fetching players:", error);
        }
    };

    const createPlayer = async () => {
        try {
            const response = await axios.post("/api/players", playerForm,
                {
                    headers: {
                        "Content-type": "multipart/form-data",
                    },
                }
            );
            return response.data;
        } catch (error) {
            console.error("Error creating player:", error);
            throw error;
        }
    };

    const fetchPlayer = async (id) => {
        try {
            const { data } = await axios.get(`/api/players/${id}`);

            Object.assign(playerForm, data);
        } catch (error) {
            console.error("Error fetching player:", error);
            throw error;
        }
    };

    const updatePlayer = async () => {
        try {
            const response = await axios.put(
                `/api/players/${playerForm.id}`,
                playerForm,
                {
                    headers: {
                        "Content-type": "multipart/form-data",
                    },
                }
            );
            return response.data;
        } catch (error) {
            console.error("Error updating player:", error);
            throw error;
        }
    };

    const deletePlayer = async (id) => {
        try {
            const response = await axios.delete(`/api/players/${id}`);
            return response.data;
        } catch (error) {
            console.error("Error deleting player:", error);
            throw error;
        }
    };

    return {
        playersData,
        playerForm,
        getPlayers,
        createPlayer,
        fetchPlayer,
        updatePlayer,
        deletePlayer,
    };
});
