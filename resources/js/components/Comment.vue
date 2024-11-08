<template>
    <div class="commenter__comment py-4 border-t border-40">
        <div class="font-light text-80 text-sm">
            <template v-if="hasCommenter">
                <router-link
                    :to="{
                      name: 'detail',
                      params: {
                        resourceName: commenter.resource,
                        resourceId: commenter.id,
                      },
                    }"
                    class="no-underline font-bold dim text-primary"
                    v-text="commenter.name"></router-link>
                {{ __('said') }}
            </template>

            <template v-else>
                {{ __('Written') }}
            </template>

            {{ date }}
        </div>

        <div class="mt-2" v-html="commentString"></div>
    </div>
</template>

<script>
export default {
    props: {
        comment: {
            type: Object,
            required: true
        }
    },

    computed: {
        commentString() {
            return this.comment.fields.find(field => field.attribute === 'comment').value;
        },

        commenter() {
            const field = this.comment.fields.find(field => field.attribute === 'commenter');
            return field ? {
                name: field.value,
                resource: field.resourceName,
                id: field.belongsToId
            } : null;
        },

        date() {
            const language = Nova.app.__('Moment Locale');
            moment.locale(language);
            const now = moment();
            const date = moment.utc(this.comment.fields.find(field => field.attribute === 'created_at').value)
                .tz(moment.tz.guess());

            if (date.isSame(now, 'minute')) {
                return Nova.app.__('just now');
            }

            if (date.isSame(now, 'day')) {
                return Nova.app.__('at') + ' ' + date.format('LT');
            }

            if (date.isSame(now, 'year')) {
                return Nova.app.__('on') + ' ' + (language === 'de' ? date.format('D MMM') : date.format('MMM D'));
            }

            return Nova.app.__('on') + ' ' + date.format('ll');
        },

        hasCommenter() {
            return Boolean(this.commenter);
        }
    }
}
</script>
