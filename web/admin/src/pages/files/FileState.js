export const initialState = {
  isLoading: false,
  files: [],
  error: null
};

export const REQUEST_FILES = "Files/REQUEST";
export const RECEIVE_FILES = "Files/RECEIVE";

export const requestFiles = () => ({
  type: REQUEST_FILES
});

export const receiveFiles = (json) => ({
  type: RECEIVE_FILES,
	files: json.items,
});

export const fetchFiles = (page) => dispatch => {
  dispatch(requestFiles());
  return fetch('http://127.0.0.1:5000/share_files')
    .then(response => response.json())
    .then(json => dispatch(receiveFiles(json)));
};

export default function FilesReducer(state = initialState, { type, files }) {
  switch (type) {
    case REQUEST_FILES:
      return {
        ...state,
        isLoading: true
      };
    case RECEIVE_FILES:
      return {
        ...state,
        isLoading: false,
				files: files,
        error: null
      };
    default:
      return state;
  }
}
