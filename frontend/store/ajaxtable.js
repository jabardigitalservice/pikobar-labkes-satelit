

// state
export const state = {
  params: {},
  pagination: {}
}

// getters 
export const getters = {
  params: state => state.params,
  pagination: state => state.pagination
}

// mutations
export const mutations = {
  SET_PARAMS (state, { params }) {
    state.params = params
  },
  SET_PAGINATION (state, { pagination }) {
    state.pagination = pagination
  },
}

// actions
export const actions = {
  setParams ({ commit }, { params }) {
    commit(types.SET_PARAMS, { params })
  },
  setPagination ({ commit }, { pagination }) {
    commit(types.SET_PAGINATION, { pagination })
  },
}