# Task Tracker CLI

A simple console task manager in PHP for tracking your tasks.  
Project: [task-tracker](https://roadmap.sh/projects/task-tracker)

## Description

Task Tracker is a CLI application for task management. You can add, update, delete tasks, change their status, and view tasks using various filters. All data is stored in a JSON file.

## Features

- ✅ Add new tasks
- ✏️ Update existing tasks
- 🗑️ Delete tasks
- 🔄 Change task status (todo, in-progress, done)
- 📋 View all tasks
- 🔍 Filter tasks by status
- 💾 Automatic saving to JSON file

## Installation

1. Ensure you have PHP installed (version 8.1 or higher)
2. Copy the project files to your desired directory

## Usage

### Add a task
```bash
php task-cli.php add "Your task description"
```

### Update a task
```bash
php task-cli.php update 1 "New task description"
```

### Delete a task
```bash
php task-cli.php delete 1
```

### Change task status
```bash
php task-cli.php mark-in-progress 1
php task-cli.php mark-done 1
```

### View tasks
```bash
# All tasks
php task-cli.php list

# Tasks with specific status
php task-cli.php list done
php task-cli.php list todo
php task-cli.php list in-progress
```

## Task Structure

Each task contains the following fields:

- `id` - unique identifier
- `description` - task description
- `status` - status (todo, in-progress, done)
- `createdAt` - creation date
- `updatedAt` - last update date

## Data Storage

Data is saved to a `tasks.json` file in `/data/tasks.json`. The file is created automatically when the application is first used.

## Usage Examples

```bash
# Add tasks
php task-cli.php add "Buy groceries"
php task-cli.php add "Complete homework"

# Mark a task as done
php task-cli.php mark-done 1

# View remaining tasks
php task-cli.php list todo
```

## Error Handling

The application handles various errors:
- Missing arguments
- Invalid task IDs
- Unknown status
- Failed to write to JSON

## Technical Details

- Written in pure PHP without external dependencies
- Uses standard PHP modules for file operations
- Supports positional command line arguments
- Meets project requirements