<template>
    <div>
        <div class="nst_search_links_container">
            <div class="nst_form_group">
                <label for="nst_search">{{ title }}</label>
                <input :placeholder="placeholder" id="nst_search" v-model="search" />
            </div>
            <div v-if="results && results.length" class="search_results">
                <ul>
                    <li class="link_url" v-for="result in results" @click="setUrl(result, $event)" :key="result.ID">{{ result.title }} <span class="type">{{ result.info }}</span></li>
                </ul>
            </div>
            <div class="selected_url">
                <p>Selected URL: {{ value.permalink }}</p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        name: 'search_url',
        props: {
            title: {
                type: String,
                default: function () {
                    return 'Search Your URL'
                }
            },
            placeholder: {
                type: String,
                default: function () {
                    return 'Search URL'
                }
            },
            value: {
                type: Object,
                default: function () {
                    return {
                        permalink: '',
                        post_id: null,
                        type: ''
                    }
                }
            }
        },
        data() {
            return {
                search: '',
                results: []
            }
        },
        watch: {
            search() {
                this.performSearch()
            }
        },
        methods: {
            performSearch() {
                let data = {
                    action: 'wp-link-ajax',
                    page: 1,
                    search: this.search,
                    _ajax_linking_nonce: ninja_split_testing_admin._link_nonce
                };
                jQuery.post(ninja_split_testing_admin.ajaxurl, data)
                    .then((response) => {
                        this.results = JSON.parse(response);
                        console.log(response);
                    })
                    .fail((error) => {

                    });
            },
            setUrl(url, $event) {
                jQuery('.link_url').removeClass('active');
                jQuery($event.target).addClass('active');
                this.value = {
                    permalink: url.permalink,
                    post_id: url.ID,
                    type: url.info
                };
                this.$emit('input', this.value);
            }
        },
        mounted() {
            this.performSearch();   
        }
    }
</script> 