export const state = () => ({
    selectedSampels: []
})

export const mutations = {
    add (state, sampelId) {
      state.selectedSampels.push(sampelId)
    },
    remove (state, sampelId) {
      state.selectedSampels.splice(state.selectedSampels.indexOf(sampelId), 1)
    },
    clear (state) {
        state.selectedSampels = []
    }
}