export const initialState = {
  isLoading: false,
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
});

export const fetchUsers = (page) => dispatch => {
  dispatch(requestUsers());
  return fetch('/share_users')
    .then(response => response.json())
    .then(json => dispatch(receiveUsers(json)));
};

export default function UsersReducer(state = initialState, { type, users }) {
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
        users: users,
        error: null
      };
    default:
      return state;
  }
}
