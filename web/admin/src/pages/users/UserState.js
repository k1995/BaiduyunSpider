export const initialState = {
  isLoading: false,
  hasMore: false,
  users: [],
  error: null
};

export const REQUEST_USERS = "Users/REQUEST";
export const RECEIVE_USERS = "Users/RECEIVE";

export const requestUsers = () => ({
  type: REQUEST_USERS
});

export const receiveUsers = (json) => ({
  type: RECEIVE_USERS,
  users: json.items,
  total: json.total,
  hasMore: json.has_more,
});

export const fetchUsers = (page = 1, size = 10) => dispatch => {
  dispatch(requestUsers());
  return fetch(`/share_users?page=${page}&size=${size}`)
    .then(response => response.json())
    .then(json => dispatch(receiveUsers(json)));
};

export default function UsersReducer(state = initialState, { type, ...payload }) {
  switch (type) {
    case REQUEST_USERS:
      return {
        ...state,
        isLoading: true
      };
    case RECEIVE_USERS:
      return {
        ...state,
        isLoading: false,
        users: payload.users,
        hasMore: payload.hasMore,
        total: payload.total,
        error: null
      };
    default:
      return state;
  }
}
