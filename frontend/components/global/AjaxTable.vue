<template id="table-template">
    <div v-bind:id="oid">
        <div v-if="config.has_entry_page || config.has_search_input" style="margin-bottom: 15px">
            <div class="form-inline d-flex justify-content-between" style="position: relative;min-height:35px;">
                <div class="pull-left" v-if="config.has_entry_page">
                    {{$t('title.show')}} &nbsp;
                    <select class="form-control-sm form-control input-s-sm inline" v-model="pagination.perpage" v-on:change="changePage()">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="999999">Semua</option>
                    </select>
                    Entri &nbsp;
                </div>
               
                <div class="pull-right table-search ml-2 pl-2" v-if="config.has_search_input">
                    {{$t('title.search')}} &nbsp;<input placeholder="Search"  v-model="search_input" @keyup="doSearchDebounce" type="text" class="form-control form-control-sm">
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div v-show="!showCustomEmptyPage" style="position:relative">
            <div v-bind:class="config.class.wrapper">
                <table class="table table-bordered table-striped "
                       v-bind:class="config.class.table">
                    <thead v-bind:class="config.class.thead" v-if="!config.disable_header || config.has_search_header">
                    <tr :is="config.custom_header" v-if="!config.disable_header && config.custom_header != ''"
                        :sort="sort" :sort-column.sync="sortColumn" :sort-dir.sync="sortDir"></tr>
                    <tr v-if="!config.disable_header">
                        <th v-if="config.has_number" class="header_no" style="width: 1%">{{$t('title.number')}}</th>
                        <th v-for="(column_name,column) in columns"
                            v-bind:style="columnsStyle ? columnsStyle[column] : {}"
                            v-on:click="sort(column)"
                            :key="column">
                            {{column_name}}
                            <sorter :sort-dir.sync="sortDir" :column-name="column"
                                    :sort-column.sync="sortColumn"></sorter>
                        </th>
                        <th v-if="config.has_action" class="header_action">{{ $t('title.actions') }}</th>
                    </tr>
                    <tr v-if="config.has_search_header">
                        <th v-bind:colspan="totalColumns" class="th_search">
                            <i class="icon-bdg_search"></i>
                            <input type="search" class="table-search form-control b-none w-full"
                                   v-bind:placeholder="config.search_placeholder ? config.search_placeholder : 'Cari'"
                                   v-model="search_input" @keyup="doSearchDebounce">
                        </th>
                    </tr>
                    </thead>
                    <tbody v-bind:class="config.class.tbody">
                    <tr v-for="(item,index) in items" :is="rowtemplate" :rowparams.sync="rowparams" :index="index"
                        :item="item" :pagination="pagination" :key="index" :config="config"></tr>
                    <tr v-if="pagination.count == 0">
                        <td v-bind:colspan="totalColumns">{{ $t('title.nodata_show') }}</td>
                    </tr>
                    </tbody>
                    <tfoot v-if="config.custom_footer && config.custom_footer != ''" :is="config.custom_footer"
                           :info.sync="info"></tfoot>
                </table>
            </div>
            <div v-if="config.has_pagination && config.pagination_type == 'jumptopage'"
                 style="margin-top: 10px;display: none" v-show="pagination.count > 0">
                <div class="row">
                    <div class="col-sm-6" style="line-height: 40px">
                        <div class="pagination-summary">
                            {{ capitalize($t('common.entry')) }}&nbsp;{{ (pagination.page - 1) * pagination.perpage + 1
                            }}&nbsp;{{ $t('title.to') }}&nbsp;{{
                            Math.min(pagination.count, pagination.page * pagination.perpage) }} {{
                            $t('common.from') }}&nbsp;{{
                            pagination.count }}&nbsp;{{ $t('common.entry') }}&nbsp;({{
                            pagination.total }} {{ $t('common.page') }})
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-right form-group pagination-summary">
                            {{ capitalize($t('common.page')) }} &nbsp;
                            <!--first page button-->
                            <a class="btn btn-default btn-xs" v-on:click="paginate('first')"><i
                                    class="os-icon os-icon-skip-back"></i></a>
                            <a class="btn btn-default btn-xs" v-on:click="paginate('previous')"><i
                                    class="os-icon os-icon-chevron-left"></i></a>
                            <input class="input-sm" type="text" size="4" v-model="pagination.page"
                                   v-on:change="changePage()">
                            <a class="btn btn-default btn-xs" v-on:click="paginate('next')"><i
                                    class="os-icon os-icon-chevron-right"></i></a>
                            <a class="btn btn-default btn-xs" v-on:click="paginate('last')"><i
                                    class="os-icon os-icon-skip-forward"></i></a>
                            &nbsp;{{ $t('common.from') }}&nbsp;{{ pagination.total }}
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div v-else-if="config.has_pagination && pagination.count > 0" class="row pagination-wrapper">
                <div class="col-sm-12 col-md-5" style="margin-top: 10px;">
                    <div class="paging_info">
                        {{ capitalize($t('common.entry')) }}&nbsp;{{ (pagination.page - 1) * pagination.perpage + 1
                        }}&nbsp;{{ $t('title.to') }}&nbsp;{{
                        Math.min(pagination.count, pagination.page * pagination.perpage) }} {{
                        $t('common.from') }}&nbsp;{{
                        pagination.count }}&nbsp;{{ $t('common.entry') }}&nbsp;({{
                        pagination.total }} {{ $t('common.page') }})
                    </div>
                </div>
                <div class="col-sm-12 col-md-7" style="margin-top: 10px;" v-if="pagination.total > 1">
                    <div class="paging paging_simple_numbers">
                        <ul class="pagination">
                            <li class="paginate_button page-item" :class="{disabled: pagination.page == 1}" v-if="pagination.page > 1">
                                <a href="#" class="page-link" v-on:click="paginate('prev')">{{ $t('title.previous') }}</a>
                            </li>
                            <li class="paginate_button page-item" :class="{active: pagination.page == page}"
                                v-for="page in arrayPage" :key="page">
                                <a href="#" class="page-link" v-if="pagination.page == page">{{page}}</a>
                                <a href="#" class="page-link" v-if="pagination.page != page" @click="paginate(page)"
                                   style="cursor:pointer">{{page}}</a>
                            </li>
                            <li class="paginate_button page-item"
                                :class="{disabled: pagination.page >= pagination.total}" v-if="pagination.page != pagination.total">
                                <a href="#" class="page-link" v-on:click="paginate('next')">{{ $t('title.next') }}</a>
                            </li>
                        </ul>
                        <!-- .page-numbers -->
                    </div>
                </div>
            </div>
            <div class="dimmed" v-if="isLoading">
                <div class="loading-indicator">
                    <img src="~/assets/img/loading.gif" width="48" height="48">
                    Sedang memuat data...
                </div>
            </div>
        </div>
        <div style="display: none" v-show="showCustomEmptyPage">
            <slot name="empty-page">
                <div class="empty-state">
                    <p>{{ $t('title.nodata_show') }}</p>
                </div>
            </slot>
        </div>
    </div>
</template>

<style scoped>
    .form-inline .form-control {
        display: inline-block;
        width: auto;
        vertical-align: middle;
    }

    @media (max-width: 800px) {
        .table-search {
            text-align: right;
        }

        .form-inline .form-control {
            width: 60%;
        }
    }

    @media (max-width: 576px) {
        .form-inline .form-control {
            width: 50%;
        }
    }

    @media (max-width: 455px) {
        .form-inline .form-control {
            width: 40%;
        }
    }

    @media (max-width: 435px) {
        .table-search {
            float: none;
            width: 100%;
        }

        .form-inline .form-control {
            width: calc(100% - 60px);
        }
    }
</style>

<style>
    .dimmed {
        z-index: 10;
        display: block;
        position: absolute;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.5);
    }

    .loading-indicator {
        background: #3eacff;
        top: 50%;
        position: absolute;
        padding: 1em;
        left: 50%;
        margin-left: auto;
        margin-right: auto;
        transform: translate(-50%, -50%);
        color: white;
    }

    .input-sm {
        padding: 0.5rem 0.7rem;
        font-size: 0.9rem;
        line-height: 1.25;
        color: #464a4c;
        /*text-align: center;*/
        background-color: #fff;
        background-image: none;
        background-clip: padding-box;
        border: 1px solid #cecece;
        border-radius: 0.25rem;
        transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    }

    div.pagination-wrapper div.paging_info {
        padding-top: 0.5em;
        white-space: nowrap;
    }

    div.pagination-wrapper div.paging {
        margin: 0;
        white-space: nowrap;
        float: right;
    }
</style>

<script>
import axios from 'axios'
import Sorter from './Sorter'
// import eventHub from '~/plugins/event-bus'
// Load store modules dynamically.
const requireContext = require.context('./table-row', false, /.*\.vue$/)

var modules = requireContext.keys()
  .map(file =>
    [file.replace(/(^.\/)|(\.vue$)/g, ''), requireContext(file)]
  )
  .reduce((modules, [name, module]) => {
    return { ...modules, [name]: module.default || module }
  }, {})
modules.Sorter = Sorter;

var debounce = require('lodash/debounce')
var size = require('lodash/size')
var capitalize = require('lodash/capitalize')
export default {
    name: 'AjaxTable',
    components: modules,
    props   : ['oid', 'url', 'urlexport', 'params', 'columns', 'config', 'rowparams', 'rowtemplate', 'columnsStyle'],
    data() {
        return {
            isLoading   : true,
            isLoadingExp: false,
            items       : [],
            info        : {},
            pagination  : {
                page    : 1,
                previous: false,
                next    : false,
                perpage : 20,
                count   : 0,
                total   : 0
            },
            search      : '',
            search_input: '',
            sortColumn  : null,
            sortDir     : null,
            detail      : {},
            showDetail  : false,
        }
    },
    methods : {
        capitalize: capitalize,
        paginate(direction) {
            if (direction === 'previous') {
                if (this.pagination.page > 1) {
                    --this.pagination.page;
                    this.changePage();
                }
            } else if (direction === 'next') {
                if (Math.floor((this.pagination.count - 1) / this.pagination.perpage) != (this.pagination.page - 1)) {
                    ++this.pagination.page;
                    this.changePage();
                }
            } else if (direction === 'first') {
                this.pagination.page = 1;
                this.changePage();
            } else if (direction === 'last') {
                this.pagination.page = Math.floor((this.pagination.count - 1) / this.pagination.perpage) + 1;
                this.changePage();
            } else {
                this.pagination.page = direction;
                this.changePage();
            }
        },
        changePage(page) {
            if (isNaN(parseInt(this.pagination.page)) || this.pagination.page < 1) {
                this.pagination.page = 1;
            }
            this.getData(this.pagination.page);
        },
        clearFilter() {
            for (var key in this.params) {
                if (this.params.hasOwnProperty(key)) {
                    this.params[key] = '';
                }
            }
            this.search          = '';
            this.search_input    = '';
            this.sortColumn      = null;
            this.sortDir         = null;
            this.pagination.page = 1;
            this.changePage();
        },
        sort(col, default_sort_dir) {
            if (this.sortColumn == col) {
                this.sortDir = default_sort_dir ? default_sort_dir : ((this.sortDir == 'asc') ? 'desc' : 'asc');
            } else {
                this.sortColumn = col;
                this.sortDir    = default_sort_dir ? default_sort_dir : 'asc';
            }
            if (this.config.local_sort) {

            } else {
                this.pagination.page = 1;
                this.changePage();
            }
        },
        doSearch() {
            this.search          = this.search_input;
            this.pagination.page = 1;
            this.changePage();
        },
        doSearchDebounce: debounce(function () {
            this.doSearch();
        }, 500),
        getData(page) {
            var that       = this;
            that.isLoading = true;
            axios.get(that.url, {
                params: {
                    page           : page,
                    perpage        : that.config.show_all ? 99999999 : that.pagination.perpage,
                    params         : that.params,
                    search         : that.search,
                    order          : that.sortColumn,
                    order_direction: that.sortDir,
                },
            }).then((response) => {
                var data = response.data;
                that.items            = data.data;
                that.info             = data.info;
                that.pagination.count = data.count;
                that.pagination.total = Math.ceil(that.pagination.count / that.pagination.perpage);
                if (that.pagination.count > 0 && that.pagination.page > Math.floor((that.pagination.count - 1) / that.pagination.perpage) + 1) {
                    that.pagination.page = Math.floor((that.pagination.count - 1) / that.pagination.perpage) + 1;
                    that.changePage();
                }
            }).catch((error) => {

            }).then(() => {
                that.isLoading = false;
            })
        },
        doRefresh(objid) {
            if (!objid || objid == this.oid) {
                this.changePage();
            }
        },
        doRefresh2(objid, params) {
             if (!objid || objid == this.oid) {
                this.params = params;
                this.changePage();
            }
        },
        export() {
            var that = this;
            that.isLoadingExp = true;
            this.$bus.$emit('download-export', 'start', this.oid);
            axios({
                url: that.urlexport,
                params: {
                    page           : 1,
                    perpage        : 99999999,
                    params         : that.params,
                    search         : that.search,
                    order          : that.sortColumn,
                    order_direction: that.sortDir,
                },
                method: 'GET',
                responseType: 'blob',
            }).then((response) => {
                const blob = new Blob([response.data], {type: response.data.type});
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                const contentDisposition = response.headers['content-disposition'];
                let fileName = 'unknown';
                if (contentDisposition) {
                    const fileNameMatch = contentDisposition.match(/filename=(.+)/);
                    if (fileNameMatch.length === 2)
                        fileName = fileNameMatch[1];
                }
                link.setAttribute('download', fileName);
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);
                that.isLoadingExp = false;
                this.$bus.$emit('download-export', 'end', this.oid);
            });
        },
    },
    computed: {
        showCustomEmptyPage: function () {
            return this.config.custom_empty_page == true && this.pagination.count == 0 && !this.isLoading && this.search == '';
        },
        totalColumns       : function () {
            return (this.config.has_number ? 1 : 0) + size(this.columns) + (this.config.has_action ? 1 : 0);
        },
        arrayPage          : function () {
            var arr   = [];
            var awal  = 1;
            var akhir = this.pagination.total;

            if (akhir > 5) akhir = 5;

            if (this.pagination.page > 3) {
                awal  = this.pagination.page - 2;
                akhir = this.pagination.page + 2;

                if ((this.pagination.total - this.pagination.page) == 1 || this.pagination.total == this.pagination.page) {
                    akhir = this.pagination.total;
                    awal  = this.pagination.total - 4;

                    if (awal == 0) {
                        awal = 1;
                    }
                }
            }

            var no = 0;
            for (var i = awal; i <= akhir; i++) {
                arr[no] = i;

                no += 1;
            }
            return arr;
        },
    },
    events  : {
        'do-search'(objid) {
            if (objid == this.oid) {
                this.doSearch();
            }
        },
        'clear-filter'(objid) {
            if (objid == this.oid) {
                this.clearFilter();
            }
        },
        'reset-search'(objid) {
            if (objid == this.oid) {
                this.search          = '';
                this.search_input    = '';
                this.sortColumn      = null;
                this.sortDir         = null;
                this.pagination.page = 1;
                this.doSearch();
            }
        },
    },
    created() {
        var that = this;
        // console.log('Event Hub : ',eventHub);
        this.$bus.$on('refresh-ajaxtable', this.doRefresh)
        this.$bus.$on('refresh-ajaxtable2', this.doRefresh2)

        // eventHub.$on('refresh-ajaxtable', this.doRefresh)
    },
    beforeDestroy() {
        this.$bus.$off('refresh-ajaxtable',this.doRefresh)
        this.$bus.$off('refresh-ajaxtable2',this.doRefresh2)
        // eventHub.$off('refresh-ajaxtable', this.doRefresh)
    },
    mounted() {
        var that = this;
        this.$nextTick(function () {
            if (that.config.default_sort) {
                if (that.config.autoload) {
                    that.sort(that.config.default_sort, that.config.default_sort_dir)
                } else {
                    that.sortColumn = that.config.default_sort;
                    that.sortDir    = that.config.default_sort_dir ? that.config.default_sort_dir : 'asc';
                }
            } else if (that.config.autoload) {
                that.changePage(1)
            }
        });
    }
}
</script>
