import {compile} from 'path-to-regexp';

export const loginRoute = compile('fortify/login');
export const logoutRoute = compile('fortify/logout');
export const authRoute = compile('api/auth');
export const forgotPasswordRoute = compile('fortify/forgot-password');
export const resetPasswordRoute = compile('fortify/reset-password');
export const chatSessionRoute = compile('api/chat-session');
export const chatSessionMessageRoute = compile<{
    chatSessionId: string;
}>('api/chat-session/:chatSessionId/chat-message');
export const userRoute = compile('api/users/:id?');
