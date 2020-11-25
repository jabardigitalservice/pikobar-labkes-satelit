<style>
    ul.timeline {
        list-style-type: none;
        position: relative;
    }
    ul.timeline:before {
        content: ' ';
        background: #d4d9df;
        display: inline-block;
        position: absolute;
        left: 29px;
        width: 2px;
        height: 100%;
        z-index: 400;
    }
    ul.timeline > li {
        margin: 20px 0;
        padding-left: 20px;
    }
    ul.timeline > li:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #22c0e8;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }
</style>

<template>
    <ul class="timeline">
        <li v-for="(log, idx) in data"  v-bind:key="idx">
            <span>{{formatDate(log.created_at)}} - {{ log.updated_by }}</span>
            <ul style="padding-left: 17px; list-style-type: disc;">   
                <div v-for="(changes, idx1) in JSON.parse(log.info)" :key="idx1">
                    <!-- {{  changes }} -->
                    <li v-for="(change, idx) in changes" :key="idx">
                        <span>
                        <b>{{ humanize(idx) }}</b>
                        {{ `${change.from} > ${change.to}` }}
                        </span>
                    </li>
                </div>
            </ul>
        </li>
    </ul>
</template>

<script>
import { humanize } from '~/utils';
import moment from 'moment';

export default {
  name: 'Timeline',
  methods: {
      formatDate(dates) {
          return moment(dates).locale("id").format("D-MMM-YYYY hh:mm:ss")
      },
      humanize

  },
  props: {
    title: { type: String, default: null },
    data: { type: Array, default: null},
  }
}
</script>
