export const initialState = {
  isLoading: false,
  files: [],
  total: 0,
  hasMore: false,
  error: null,
  task: {
    pushing: false,
    error: null
  }
};

export const REQUEST_FILES = "Files/REQUEST";
export const RECEIVE_FILES = "Files/RECEIVE";
export const TASK_PUSHING = "TASK/PUSH_PUSHING";
export const TASK_PUSH_SUCCESS = "TASK/PUSH_SUCCESS";
export const TASK_PUSH_ERROR = "Urls/PUSH_ERROR";


export const requestFiles = () => ({
  type: REQUEST_FILES
});

export const receiveFiles = (json) => ({
  type: RECEIVE_FILES,
  files: json.items,
  total: json.total,
  hasMore: json.has_more,
});

export const taskPushError = (error) => ({
  type: TASK_PUSH_ERROR,
  error
});

export const pushUrl = (url) => dispatch => {
  let formData = new FormData();
  formData.append('url', url);
  dispatch(({ type: TASK_PUSHING}));
  return fetch('/addUrl', {
    method: 'POST',
    body: formData
  }).then(response => response.text())
    .then((err) => {
      if(err === "ok") {
        dispatch(({ type: TASK_PUSH_SUCCESS}));
      }else{
        dispatch(taskPushError(err));
        return err;
      }
  });
};

export const fetchFiles = (page = 1, size = 10) => dispatch => {
  dispatch(requestFiles());
  return fetch(`/share_files?page=${page}&size=${size}`)
    .then(response => response.json())
    .then(json => dispatch(receiveFiles(json)));
};

export default function FilesReducer(state = initialState, { type, ...payload }) {
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
        files: payload.files,
        total: payload.total,
        hasMore: payload.has_more,
        error: null
      };
    case TASK_PUSHING:
      return {
        ...state,
        task: {
          pushing: true,
          error: null
        }
      };
    case TASK_PUSH_SUCCESS:
      return {
        ...state,
        task: {
          pushing: false,
          error: null
        }
      };
    case TASK_PUSH_ERROR:
      return {
        ...state,
        task: {
          pushing: false,
          error: payload.error
        }
      };
    default:
      return state;
  }
}
