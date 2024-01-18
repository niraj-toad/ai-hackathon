/* eslint-disable max-len */
// !!! This is a generated file. Do not change this file manually.

const Permission = {
    /** Grants access to the staff panel in the frontend. */
    AccessStaffPanel: 'access_staff_panel',
    /** Grants access to user details. */
    ViewUsers: 'view_users',
} as const;

type Permission = typeof Permission[keyof typeof Permission];

export default Permission;
