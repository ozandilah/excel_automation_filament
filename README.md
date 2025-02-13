# Excel Data Automation

This project is designed to automate the processing of Excel data using Laravel. It provides a streamlined way to handle large datasets and perform various operations on Excel files.

## Features

- Import and export Excel files
- Data validation and transformation
- Automated data processing
- Integration with Laravel's Eloquent ORM
- Merge data from multiple Excel files based on unique identifiers

## Installation

To get started with this project, follow these steps:

1. **Clone the repository:**
    ```bash
    git clone https://github.com/yourusername/excelData_automation.git
    cd excelData_automation
    ```

2. **Install dependencies:**
    ```bash
    composer install
    npm install
    ```

3. **Set up environment variables:**
    Copy the `.env.example` file to `.env` and update the necessary environment variables.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Run migrations:**
    ```bash
    php artisan migrate
    ```

5. **Serve the application:**
    ```bash
    php artisan serve
    ```

## Usage

1. **Importing Excel Data:**
    - Upload your Excel files through the provided interface.
    - The data will be validated and imported into the database.

2. **Merging Excel Data:**
    - The system will automatically merge data from multiple Excel files based on the unique identifier (NIK).
    - Empty fields in the first Excel file will be filled with corresponding values from the second Excel file.

3. **Exporting Data to Excel:**
    - Select the data you want to export.
    - Click the export button to download the merged data as an Excel file.

4. **Automated Data Processing:**
    - Set up scheduled tasks to automate data processing using Laravel's task scheduling.

## Contributing

If you would like to contribute to this project, please fork the repository and submit a pull request. We welcome all contributions!

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

## Contact

For any questions or support, please open an issue on the GitHub repository or contact the project maintainer at your.email@example.com.

