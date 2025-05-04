import { ref, computed } from 'vue';
import axios from '@/axios';

export const useDocumentsStore = () => {
  const documents = ref([]);
  const isLoading = ref(false);
  const errorMessage = ref('');


  const fetchDocuments = async () => {
    isLoading.value = true;
    try {
      const response = await axios.get('/documents/to-sign');

      documents.value = response.data;
    } catch (error) {
      errorMessage.value = 'Error fetching documents';
      console.error(error);
    } finally {
      isLoading.value = false;
    }
  };

  const signDocument = async (documentId) => {
    try {
      const response = await axios.post(`/documents/${documentId}/sign`);
      documents.value = documents.value.filter(doc => doc.id !== documentId);

      return response.data;
    } catch (error) {
      console.error('Error signing document', error);
    }
  };

  return {
    documents,
    isLoading,
    errorMessage,
    fetchDocuments,
    signDocument,
  };
};
