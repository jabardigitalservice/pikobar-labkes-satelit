import axios from 'axios'

// state
export const state = () => ({
  lab_pcr: [],
  jenis_sampel: [],
})

// getters
export const getters = {
  lab_pcr: state => state.lab_pcr,
  jenis_sampel: state => state.jenis_sampel,
}

// mutations
export const mutations = {

  FETCH_LAB_PCR_SUCCESS (state, lab_pcr) {
    state.lab_pcr = lab_pcr
  },

  FETCH_LAB_PCR_FAILURE (state) {
  },

  FETCH_JENIS_SAMPEL_SUCCESS (state, jenis_sampel) {
    state.jenis_sampel = jenis_sampel
  },

  FETCH_JENIS_SAMPEL_FAILURE (state) {
  },

}

// actions
export const actions = {
  async fetchLabPCR ({ commit }) {
    try {
      let resp = await axios.get('/lab-pcr-option')

      commit('FETCH_LAB_PCR_SUCCESS', resp.data)
    } catch (e) {
      this.$toast.error("Gagal memuat data opsi Lab PCR", {
        icon: "times",
        iconPack: "fontawesome",
        duration: 5000
      });
      commit('FETCH_LAB_PCR_FAILURE')
    }
  },
  async fetchJenisSampel ({ commit }) {
    try {
      let resp = await axios.get('/jenis-sampel-option')

      commit('FETCH_JENIS_SAMPEL_SUCCESS', resp.data)
    } catch (e) {
      this.$toast.error("Gagal memuat data opsi jenis sampel", {
        icon: "times",
        iconPack: "fontawesome",
        duration: 5000
      });
      commit('FETCH_JENIS_SAMPEL_FAILURE')
    }
  },
}
