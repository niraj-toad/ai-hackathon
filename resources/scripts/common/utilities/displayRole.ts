import Role from '@/common/enums/Role';

export default function displayRole(role: Role | undefined): string {
    switch (role) {
        case Role.Admin:
            return 'Admin';
        case Role.User:
            return 'User';
        case undefined:
            return 'Guest';
    }
}
