<template>
    <div :id="modal_id" class="modal fade">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{  title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <slot name="body"></slot>
            </div>
            <slot name="footer">
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div> -->
            </slot>
            
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CustomModal',
    props: ['modal_id', 'title'],
    
    data(){
        
        return {}
    }
}
</script>

<style scoped>

</style>