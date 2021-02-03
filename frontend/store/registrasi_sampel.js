export const state = () => ({
  selectedSampels: [],
  selected: []
})

export const mutations = {
  add(state, sampelId) {
    const findSampel = state.selectedSampels.find(el => el == sampelId) || null;
    if (!findSampel) {
      state.selectedSampels.push(sampelId)
    }
  },
  remove(state, sampelId) {
    state.selectedSampels.splice(state.selectedSampels.indexOf(sampelId), 1)
  },
  clear(state) {
    state.selectedSampels = [],
    state.selected = []
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
}