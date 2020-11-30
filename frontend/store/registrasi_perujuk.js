export const state = () => ({
  selectedSampels: [],
  selected: []
})

export const mutations = {
  add(state, sampelId) {
    let findsampel = state.selectedSampels.find(el => el == sampelId) || null;
    if (!findsampel) {
      state.selectedSampels.push(sampelId)
    }
  },
  remove(state, sampelId) {
    state.selectedSampels.splice(state.selectedSampels.indexOf(sampelId), 1)
  },
  clear(state) {
    state.selectedSampels = []
  },
  addMultiple(state, sampelId) {
    for (let i = 0; i < sampelId.length; i++) {
      const findSample = state.selectedSampels.find(el => el === sampelId[i]);
      if (!findSample) {
        state.selectedSampels.push(sampelId[i])
      }
    }
  },
  removeMultiple(state, sampelId) {
    for (let i = 0; i < sampelId.length; i++) {
      state.selectedSampels.splice(state.selectedSampels.indexOf(sampelId[i]), 1);
    }
  },
  clearSelected(state) {
    state.selected = []
  }
}