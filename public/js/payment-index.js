// Payment index - Report type toggle
function toggleDateFields() {
    const reportType = document.getElementById('reportType');
    const academicYearField = document.getElementById('academicYearField');
    const dateRangeFields = document.getElementById('dateRangeFields');

    if (reportType && academicYearField && dateRangeFields) {
        const type = reportType.value;

        if (type === 'date_range') {
            academicYearField.style.display = 'none';
            dateRangeFields.style.display = 'block';
        } else {
            academicYearField.style.display = 'block';
            dateRangeFields.style.display = 'none';
        }
    }
}
