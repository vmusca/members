<template>

    <div>
        <div v-if="initialised">
            <button class="button is-success has-margin-bottom-large"
                @click="member=factory()"
                :disabled="member">
                <span class="icon is-small">
                    <fa icon="plus"/>
                </span>
                <span>
                    {{ __('New member') }}
                </span>
            </button>
            <div class="control has-icons-left has-icons-right is-pulled-right"
                v-if="members.length > 3">
                <input class="member-search input"
                    type="text"
                    :placeholder="__('Filter members')"
                    v-model="query">
                <span class="icon is-small is-left">
                    <fa icon="search"/>
                </span>
                <span class="icon is-small is-right clear-button"
                    v-if="query"
                    @click="query = null">
                    <a class="delete is-small"/>
                </span>
            </div>
        </div>
        <h4 class="title is-4 has-text-centered"
            v-if="!initialised && loading">
            {{ __('Loading') }}
            <span class="icon is-small has-margin-left-medium">
                <fa icon="spinner"
                    size="xs"
                    spin/>
            </span>
        </h4>
        <div class="columns is-multiline" v-else>
            <div class="column is-one-third-widescreen is-half-tablet"
                v-if="member">
                <member :member="member"
                    @cancel="member = null"
                    @create="members.unshift($event); member = null"/>
            </div>
            <div class="column is-one-third-widescreen is-half-tablet"
                v-for="(member, index) in filteredMembers"
                :key="index">
                <member :member="member"
                    @destroy="members.splice(index, 1)"/>
            </div>
            <div class="column"
                v-if="members.length === 0">
                <h3 class="subtitle is-3 has-text-centered">
                    {{ __('No members were created yet') }}
                </h3>
            </div>
        </div>
    </div>

</template>

<script>

import { library } from '@fortawesome/fontawesome-svg-core';
import { faPlus, faSearch, faSpinner } from '@fortawesome/free-solid-svg-icons';
import Member from '../../../components/enso/members/Member.vue';

library.add(faPlus, faSearch, faSpinner);

export default {
    components: { Member },

    data() {
        return {
            loading: false,
            initialised: false,
            members: [],
            member: null,
            query: null,
        };
    },

    computed: {
        filteredMembers() {
            return this.query
                ? this.members.filter(({ edit, name }) =>
                    edit || name.toLowerCase()
                        .indexOf(this.query.toLowerCase()) > -1)
                : this.members;
        },
    },

    created() {
        this.get();
    },

    methods: {
        get() {
            this.loading = true;

            axios.get(route('administration.members.index'))
                .then(({ data }) => {
                    this.members = data;
                    this.loading = false;
                    this.initialised = true;
                })
                .catch(error => this.handleError(error));
        },
        factory() {
            return {
                id: null,
                name: null,
                userIds: [],
                users: [],
                edit: true,
            };
        },
    },
};

</script>

<style lang="scss" scoped>

    .control.has-icons-right {
        .icon.clear-button {
            pointer-events: all;
        }

        input.member-search {
            width: 150px;
        }
    }

</style>
