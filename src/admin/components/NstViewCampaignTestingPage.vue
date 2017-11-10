<template>
    <div class="nst_view_testing_page">
        <el-row v-loading="loading">
            <div class="m-l-20 m-t-10" v-if="campaign.post_id && !loading">
                <el-row>
                    <el-col :span="12">
                        <h2><i class="el-icon-document"></i> Testing Pages</h2>
                    </el-col>
                    <el-col :span="12">
                        <div class="right">
                            <el-button type="primary" @click="showNewTestForm(false)" icon="plus"> Add New Test
                            </el-button>
                        </div>
                    </el-col>
                </el-row>
                <hr>
                <el-row :gutter="30">
                    <el-col :md="12" :sm="24" :key="key" v-for="(item, key) in testing_pages"
                            class="m-b-20">
                        <el-card class="box-card">
                            <div slot="header" class="clearfix">
                                <span class="label text_elipsis">{{item.title}}</span>
                                <i class="el-icon-setting action settings" @click="setEditDialogVisible(item)"></i>
                                <el-switch
                                        v-model="item.active"
                                        style="float: right"
                                        @change="testingPageStatusChanged(item.id, item.active)"
                                        on-text="Active"
                                        off-text="Draft"
                                        :width="75"
                                        off-color="orange">
                                </el-switch>

                            </div>
                            <div>
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="label">Title</td>
                                        <td class="data text_elipsis td-char-limit">{{item.title}}</td>
                                    </tr>

                                    <tr>
                                        <td class="label">URL</td>
                                        <td class="data text_elipsis td-char-limit"><a
                                                :href="item.target_url">{{item.target_url}}</a></td>
                                    </tr>
                                    <tr>
                                        <td class="label">Traffic Priority</td>
                                        <td class="data text_elipsis td-char-limit">{{item.traffic_split_amount}}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Publish</td>
                                        <td class="data text_elipsis td-char-limit">{{item.created_at}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>
                <div v-show="!testing_pages.length" class="messages">
                    <p>You don't have any test pages. Please few more</p>
                </div>
            </div>
        </el-row>

        <!--modals-->
        <div class="modal m-t-35">
            <el-dialog
                    title="Add a new Test Page"
                    :visible.sync="addTestVisible"
                    width="30%"
                    :before-close="handleClose">
                <el-form :model="current_working_test">
                    <el-form-item label="Title">
                        <el-input v-model="current_working_test.title" auto-complete="off"></el-input>
                    </el-form-item>

                    <div class="block m-t-10">
                        <span class="demonstration">Traffic Split Amount ( {{ current_working_test.traffic_split_amount
                            }} )</span>
                        <el-slider
                                v-model="current_working_test.traffic_split_amount"
                                :step="1"
                                :max="10"
                                :min="1"
                                show-stops>
                        </el-slider>
                    </div>

                    <nst_url_search v-model="selected_url" :title="'Select Your Target Link'"></nst_url_search>

                </el-form>
                <span slot="footer" class="dialog-footer">
					    <el-button @click="addTestVisible = false">Cancel</el-button>
					    <el-button type="primary" icon="el-icon-arrow-right" @click="addNewTest()">Add</el-button>
					  </span>
            </el-dialog>
        </div>

        <div class="modal m-t-35">
            <el-dialog
                    title="Edit Test"
                    :visible.sync="editTestVisible"
                    width="30%">
                <el-form :model="current_working_test">
                    <el-form-item label="Title">
                        <el-input v-model="current_working_test.title" auto-complete="off"></el-input>
                    </el-form-item>

                    <div class="block m-t-10">
                        <span class="demonstration">Traffic Split Amount ( {{ current_working_test.traffic_split_amount
                            }} )</span>
                        <el-slider
                                v-model="current_working_test.traffic_split_amount"
                                :step="1"
                                :max="10"
                                :min="1"
                                show-stops>
                        </el-slider>
                    </div>
                    <nst_url_search v-model="selected_url" :title="'Select Your Target Link'"></nst_url_search>
                </el-form>
                <span slot="footer" class="dialog-footer">
					    <el-button @click="editTestVisible = false">Cancel</el-button>
					    <el-button type="primary" icon="el-icon-arrow-right" @click="updateTest()">Update</el-button>
					  </span>
            </el-dialog>
        </div>

    </div>
</template>

<script>
    import NstUrlSearch from './NstUrlSearch.vue';
    import { forEach } from 'lodash';
    export default {
        name: 'nst_view_campaign_testing_page',
        components: {
            'nst_url_search': NstUrlSearch
        },
        props: ['campaign', 'loading'],
        data() {
            return {
                addTestVisible: false,
                editTestVisible: false,
                current_working_test: {
                    title: '',
                    target_post_id: '',
                    target_url: '',
                    traffic_split_amount: 2
                },
                testing_pages: [],
                selected_url: {},
                campaign_id: parseInt(this.$route.params.id)
            }
        },
        methods: {
            fetchAllTestingPage() {
                jQuery.get(ajaxurl, {
                    action: 'routes',
                    target_action: 'get-all-testing-page',
                    campaign_id: this.campaign_id
                })
                    .done((res) => {
                        forEach(res.data.pages, (page) => {
                            page.active = (page.status == 'active') ? true : false;
                        });
                        this.testing_pages = res.data.pages;
                    })
                    .fail((err) => {
                        console.log(err)
                    });
            },
            showNewTestForm(testPage) {
                if (!testPage) {
                    this.current_working_test = {
                        title: '',
                        target_post_id: '',
                        target_url: '',
                        traffic_split_amount: 2
                    };
                    this.selected_url = {};
                    this.addTestVisible = true;
                }
            },
            addNewTest() {
                jQuery.post(ajaxurl, {
                    action: 'routes',
                    target_action: 'store-campaign-testing-page',
                    title: this.current_working_test.title,
                    target_post_id: this.selected_url.post_id,
                    target_url: this.selected_url.permalink,
                    traffic_split_amount: this.current_working_test.traffic_split_amount,
                    campaign_id: this.campaign_id
                })
                    .done((response) => {
                        this.$message({
                            showClose: true,
                            message: response.data.message,
                            type: 'success'
                        });
                        this.testing_pages.push(response.data.test_page);
                        this.handleClose();
                    })
                    .fail((error) => {
                        this.$message({
                            showClose: true,
                            message: error.responseJSON.data.message,
                            type: 'error'
                        });
                    })
            },
            setEditDialogVisible(item) {
                this.current_working_test = Object.assign({}, this.current_working_test, item);
                this.current_working_test.traffic_split_amount = parseInt(this.current_working_test.traffic_split_amount);
                this.selected_url = {
                    permalink: item.target_url,
                    post_id: item.target_post_id
                };
                this.editTestVisible = true;
            },
            updateTest() {
                jQuery.post(ajaxurl, {
                    action: 'routes',
                    target_action: 'update-testing-page',
                    title: this.current_working_test.title,
                    target_post_id: this.selected_url.post_id,
                    target_url: this.selected_url.permalink,
                    traffic_split_amount: this.current_working_test.traffic_split_amount,
                    campaign_id: this.campaign_id,
                    id: this.current_working_test.id
                })
                    .done((response) => {
                        this.$message({
                            showClose: true,
                            message: response.data.message,
                            type: 'success'
                        });
                        this.editTestVisible = false;
                        this.fetchAllTestingPage();
                    })
                    .fail((error) => {
                        this.$message({
                            showClose: true,
                            message: error.responseJSON.data.message,
                            type: 'error'
                        });
                    })
            },
            testingPageStatusChanged(id, status) {
                jQuery.get(ajaxurl, {
                    action: 'routes',
                    target_action: 'update-testing-page-status',
                    update_status: {
                        id: id,
                        status: status ? 'active' : 'inactive'
                    }

                })
                    .done((res) => {
                        this.fetchAllTestingPage();
                        this.$message({
                            showClose: true,
                            message: res.data.message,
                            type: 'success'
                        });
                    })
                    .fail((err) => {
                        console.log(err)
                    })

            },

            editHandleClose() {
                this.editDialogVisible = false;
            },
            handleClose() {
                this.addTestVisible = false
            },


        },
        mounted() {
            this.fetchAllTestingPage();
        }
    }
</script>

<style lang="scss">
    .post-type-1 {
        margin-right: 10px;
    }

    .post-type-2 {
        .el-select {
            width: 100%;
        }
    }

    .modal {
        .el-dialog__wrapper {
            .el-dialog {
                padding: 20px;
            }
        }
    }

    .label {
        width: 200px;
        font-size: 15px;
        color: black;
        font-weight: 500;
        padding: 6px 0px;
    }

    .data {
        font-size: 15px;
        color: black;
        font-weight: 300;
    }

    .settings {
        float: right;
        font-size: 20px;
        margin-left: 10px;
        margin-top: 2px;
    }
</style>