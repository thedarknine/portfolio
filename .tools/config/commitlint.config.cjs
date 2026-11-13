// Conventional commits, checked on pull requests by the "commitlint" job of .github/workflows/ci.yml.
// Kept in sync with the git_commit_message task in .tools/config/grumphp.yml (local commit-msg hook).
module.exports = {
    rules: {
        'type-enum': [2, 'always', ['build', 'chore', 'ci', 'docs', 'feat', 'fix', 'perf', 'refactor', 'revert', 'style', 'test']],
        'type-case': [2, 'always', 'lower-case'],
        'type-empty': [2, 'never'],
        'subject-empty': [2, 'never'],
        'subject-full-stop': [2, 'never', '.'],
        'header-max-length': [2, 'always', 72],
        'body-leading-blank': [2, 'always'],
        'body-max-line-length': [2, 'always', 100],
        'footer-leading-blank': [2, 'always'],
    },
};
