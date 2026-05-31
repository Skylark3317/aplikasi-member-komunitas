const PostCategory = {
    All: {
        value: "",
        label: "Semua"
    },
    News: {
        value: "berita",
        label: "Berita"
    },
    Event: {
        value: "acara",
        label: "Acara"
    }
};

PostCategory.from = function (value) {
    for (const [k, v] of Object.entries(PostCategory)) {
        if (v.value === value) {
            return PostCategory[k];
        }
    }

    return null;
};

PostCategory.entries = function () {
    return Object.entries(PostCategory).filter(([, v]) => typeof v === "object");
}

export default PostCategory;
