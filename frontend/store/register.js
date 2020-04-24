// state
export const state = () => ({
  id: null,
  data: null
})

// getters
export const getters = {
  id: state => state.id,
  data: state => state.data,
}

// mutations
export const mutations = {
  SET_ID (state, payload) {
    state.id = payload
  },

  SET_DATA (state, payload) {
    state.data = payload
  },
}

// actions
export const actions = {
  saveData ({ commit, dispatch }, { id, data }) {
    commit('SET_ID', id)
    commit('SET_DATA', data)
  },
}
