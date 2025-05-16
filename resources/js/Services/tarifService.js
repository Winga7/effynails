// Service de gestion des tarifs avec API
import axios from "axios";

/**
 * Service de gestion des tarifs
 * Gère toutes les opérations CRUD liées aux tarifs
 */
const tarifService = {
    /**
     * Récupère la liste des tarifs
     * @returns {Promise<Array>} Liste des tarifs
     */
    async getTarifs() {
        try {
            const response = await axios.get("/api/tarifs");
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    /**
     * Ajoute un nouveau tarif
     * @param {Object} tarif - Données du tarif à ajouter
     * @returns {Promise<Object>} Tarif créé
     */
    async addTarif(tarif) {
        try {
            const response = await axios.post("/api/tarifs", tarif);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    /**
     * Met à jour un tarif existant
     * @param {Object} tarif - Données du tarif à mettre à jour
     * @returns {Promise<Object>} Tarif mis à jour
     */
    async updateTarif(tarif) {
        try {
            const response = await axios.put(`/api/tarifs/${tarif.id}`, tarif);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    /**
     * Supprime un tarif
     * @param {number} id - ID du tarif à supprimer
     * @returns {Promise<void>}
     */
    async deleteTarif(id) {
        try {
            await axios.delete(`/api/tarifs/${id}`);
        } catch (error) {
            throw error;
        }
    },
};

export default tarifService;
