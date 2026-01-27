import os

def generate_tree(startpath):
    ignore_dirs = {'.git', 'vendor', 'node_modules', '.idea', '.vscode', '__pycache__'}
    ignore_files = {'.DS_Store', 'Thumbs.db'}

    print(f"{os.path.basename(startpath)}/")
    
    for root, dirs, files in os.walk(startpath):
        # Filter directories in-place
        dirs[:] = [d for d in dirs if d not in ignore_dirs]
        
        level = root.replace(startpath, '').count(os.sep)
        indent = ' ' * 4 * (level)
        
        subindent = ' ' * 4 * (level + 1)
        
        # Don't print the root folder again
        if root != startpath:
             print(f"{indent}{os.path.basename(root)}/")

        for f in files:
            # Skip temp files
            if f.startswith('ci_session') or f.startswith('debugbar_') or f.startswith('log-'):
                continue
            if f not in ignore_files:
                print(f"{subindent}{f}")

if __name__ == "__main__":
    import sys
    # Redirect stdout to file manually to ensure utf-8 if needed, 
    # OR just write to file directly. Use file write for safety.
    with open('project_tree.txt', 'w', encoding='utf-8') as f:
        sys.stdout = f
        generate_tree('.')
