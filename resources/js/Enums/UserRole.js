const UserRole = {
    Member: {
        value: "member",
        label: "Member"
    },
    Staff: {
        value: "staff",
        label: "Petugas"
    },
    Finance: {
        value: "finance",
        label: "Keuangan"
    },
    Leader: {
        value: "leader",
        label: "Ketua"
    },
    SuperAdmin: {
        value: "super_admin",
        label: "Super Admin"
    }
};

UserRole.from = function (value) {
    for (const [k, v] of Object.entries(UserRole)) {
        if (v.value === value) {
            return UserRole[k];
        }
    }

    return null;
};

UserRole.entries = function () {
    return Object.entries(UserRole).filter(([, v]) => typeof v === "object");
}

export default UserRole;

