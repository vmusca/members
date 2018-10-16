<template>
    <info-box :class="[
        'has-background-light raises-on-hover',
        { 'is-danger': member.edit && !member.id },
        { 'is-warning': member.edit && member.id },
        { 'is-info': !member.edit && member.users.length === 0 },
    ]">
        <info-item>
            <label slot="left"
                class="label">
                <input class="input member-name"
                    v-focus
                    v-model="member.name"
                    v-if="member.edit">
                <span v-else>{{ member.name }}</span>
            </label>
            <div slot="right"
                class="has-text-right">
                <a class="button is-naked animated fadeIn"
                    v-if="!member.edit"
                    @click="member.edit = true">
                    <span class="icon">
                        <fa icon="pencil-alt" size="sm"/>
                    </span>
                </a>
                <span class="animated fadeIn"
                    v-else>
                    <a class="button is-naked is-outlined"
                        @click="$emit('cancel');member.edit = false">
                        <span class="icon">
                            <fa icon="ban"/>
                        </span>
                    </a>
                    <a class="button is-naked is-success is-outlined"
                        :disabled="!member.name"
                        @click="store();">
                        <span class="icon">
                            <fa icon="check" size="sm"/>
                        </span>
                    </a>
                    <a class="button is-naked is-danger is-outlined"
                        @click="destroy"
                        v-if="member.id !== null">
                        <span class="icon">
                            <fa icon="trash"/>
                        </span>
                    </a>
                </span>
            </div>
        </info-item>
        <div class="is-flex avatar-list">
            <figure class="image is-32x32 has-margin-right-small has-margin-bottom-small"
                v-for="user in member.users"
                :key="user.id"
                v-tooltip="user.name">
                <img class="is-rounded"
                    :src="avatar(user.avatar.id)">
            </figure>
            <span v-if="!member.edit && !loading && member.users.length === 0"
                class="has-text-muted is-italic has-margin-bottom-small">
                {{ __('No users yet') }}
            </span>
        </div>
        <transition
            enter-active-class="fadeIn"
            leave-active-class="fadeOut">
            <div class="has-margin-bottom-large has-margin-top-large animated"
                v-if="member.edit && member.name">
                <label slot="left"
                    class="label">
                    {{ __('Members') }}:
                </label>
                <vue-select v-model="member.userIds"
                    multiple
                    source="administration.users.options"
                    label="person.name"/>
            </div>
        </transition>
    </info-box>
</template>

<script>
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBan, faPencilAlt, faTrash, faCheck }
    from '@fortawesome/free-solid-svg-icons';
import { VTooltip } from 'v-tooltip';
import InfoBox from '../bulma/InfoBox.vue';
import InfoItem from '../bulma/InfoItem.vue';
import VueSelect from '../select/VueSelect.vue';

library.add([faBan, faPencilAlt, faTrash, faCheck]);
export default {
    name: 'Member',

    components: { InfoBox, InfoItem, VueSelect },

    directives: { tooltip: VTooltip },

    props: {
        member: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            loading: false,
        };
    },

    methods: {
        store() {
            this.loading = true;

            axios.post(route('administration.members.store'), this.member)
                .then(({ data }) => {
                    this.loading = false;
                    this.$toastr.success(data.message);
                    this.member.users = data.member.users;
                    this.member.id = data.member.id;
                    this.member.edit = false;
                    this.$emit('create', this.member);
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.$toastr.warning(this.__('Choose another name'));
                        return;
                    }
                    this.handleError(error);
                });
        },
        destroy() {
            this.loading = true;

            axios.delete(route('administration.members.destroy', this.member.id))
                .then(({ data }) => {
                    this.loading = false;
                    this.$toastr.success(data.message);
                    this.member.edit = false;
                    this.$emit('destroy');
                }).catch(error => this.handleError(error));
        },
        avatar(id) {
            return route('core.avatars.show', id);
        },
    },
};
</script>

<style lang="scss" scoped>

    .member-name {
        width: 200px;

        border-top: unset;
        border-left: unset;
        border-right: unset;
        box-shadow: unset;
        border-radius: 0;

        &:focus {
            box-shadow: unset;
        }
    }

    .avatar-list {
        margin-left: 1em;
        flex-flow: wrap;

        figure {
            transition: margin 0.2s;
            -webkit-transition: margin 0.2s;
            transition: transform .2s;
            -webkit-transition: transform 0.2s;
            margin-left: -1em;

            &:hover {
                margin-left: -0.5em;
                margin-right: 1em;
                transform: scale(1.3);
                z-index: 2;
            }

            img {
                border: 2px solid #f4f6fb;
                border-radius: 50%;

                &:hover {
                    border: none;
                }
            }
        }
    }

</style>
