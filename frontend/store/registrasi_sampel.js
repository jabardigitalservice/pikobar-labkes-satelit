export const state = () => ({
  selectedSampels: [],
  selected: []
})

export const mutations = {
  add(state, sampel) {
    const findSampel = state.selectedSampels.find(el => el.id == sampel.id) || state.selectedSampels.find(el => el.name == sampel.name) || null;
    if (!findSampel) {
      state.selectedSampels.push(sampel)
    }
  },
  remove(state, sampel) {
    state.selectedSampels.splice(state.selectedSampels.indexOf(sampel), 1)
  },
  clear(state) {
    state.selectedSampels = []
    state.selected = []
  },
  addMultiple(state, sampel) {
    for (let i = 0; i < sampel.length; i++) {
      const findSample = state.selectedSampels.find(el => el.id === sampel[i].id) || state.selectedSampels.find(el => el.name === sampel[i].name) || null;
      if (!findSample) {
        state.selectedSampels.push(sampel[i])
      }
    }
  },
  removeMultiple(state, sampel) {
    for (let i = 0; i < sampel.length; i++) {
      state.selectedSampels.splice(state.selectedSampels.indexOf(sampel[i]), 1);
    }
  },
}