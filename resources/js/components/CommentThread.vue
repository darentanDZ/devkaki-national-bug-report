<template>
    <div class="space-y-4">
        <div v-for="comment in comments" :key="comment.id" :class="{ 'ml-8': comment.parent_id }">
            <div class="bg-white rounded-lg p-4 shadow">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="font-semibold">{{ comment.user?.name }}</span>
                        <span class="text-sm text-gray-500 ml-2">
                            {{ formatDate(comment.created_at) }}
                            <span v-if="comment.is_edited" class="ml-1 text-xs">(edited)</span>
                        </span>
                    </div>
                    <div v-if="canModify(comment)" class="flex gap-2">
                        <button
                            v-if="!isEditing(comment.id)"
                            @click="startEdit(comment)"
                            class="text-blue-600 text-sm hover:underline"
                        >
                            Edit
                        </button>
                        <button
                            @click="deleteComment(comment.id)"
                            class="text-red-600 text-sm hover:underline"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <div v-if="isEditing(comment.id)">
                    <textarea
                        v-model="editContent"
                        class="w-full border rounded p-2 mb-2"
                        rows="3"
                    ></textarea>
                    <div class="flex gap-2">
                        <button
                            @click="saveEdit(comment.id)"
                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                        >
                            Save
                        </button>
                        <button
                            @click="cancelEdit"
                            class="bg-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-400"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
                <p v-else class="text-gray-700">{{ comment.content }}</p>

                <button
                    v-if="authStore.isAuthenticated && !comment.parent_id"
                    @click="toggleReply(comment.id)"
                    class="text-blue-600 text-sm mt-2 hover:underline"
                >
                    {{ showReplyForm === comment.id ? 'Cancel' : 'Reply' }}
                </button>

                <div v-if="showReplyForm === comment.id" class="mt-3">
                    <textarea
                        v-model="replyContent"
                        placeholder="Write a reply..."
                        class="w-full border rounded p-2 mb-2"
                        rows="3"
                    ></textarea>
                    <button
                        @click="submitReply(comment.id)"
                        :disabled="!replyContent.trim()"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50"
                    >
                        Post Reply
                    </button>
                </div>

                <!-- Render replies -->
                <CommentThread
                    v-if="comment.replies && comment.replies.length"
                    :comments="comment.replies"
                    :bugId="bugId"
                    @comment-deleted="$emit('comment-deleted')"
                    @comment-updated="$emit('comment-updated')"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { commentAPI } from '../services/api';

const props = defineProps({
    comments: {
        type: Array,
        required: true
    },
    bugId: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['comment-deleted', 'comment-updated']);

const authStore = useAuthStore();
const editingId = ref(null);
const editContent = ref('');
const showReplyForm = ref(null);
const replyContent = ref('');

const canModify = (comment) => {
    return authStore.currentUser?.id === comment.user_id || authStore.isModerator;
};

const isEditing = (id) => editingId.value === id;

const startEdit = (comment) => {
    editingId.value = comment.id;
    editContent.value = comment.content;
};

const cancelEdit = () => {
    editingId.value = null;
    editContent.value = '';
};

const saveEdit = async (id) => {
    try {
        await commentAPI.update(id, { content: editContent.value });
        emit('comment-updated');
        cancelEdit();
    } catch (error) {
        console.error('Failed to update comment:', error);
    }
};

const deleteComment = async (id) => {
    if (!confirm('Are you sure you want to delete this comment?')) return;
    try {
        await commentAPI.delete(id);
        emit('comment-deleted');
    } catch (error) {
        console.error('Failed to delete comment:', error);
    }
};

const toggleReply = (id) => {
    showReplyForm.value = showReplyForm.value === id ? null : id;
    replyContent.value = '';
};

const submitReply = async (parentId) => {
    try {
        await commentAPI.create({
            bug_id: props.bugId,
            parent_id: parentId,
            content: replyContent.value
        });
        emit('comment-updated');
        replyContent.value = '';
        showReplyForm.value = null;
    } catch (error) {
        console.error('Failed to post reply:', error);
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>
